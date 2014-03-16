<?php

/**
 * Class to parse a CSV file and insert it into a MySQL database
 */
class CsvParser {
// Class constants

    /**
     * Data are formatted in rows. Field names are located in the first row
     */

    const dataInRows = 1;

    /**
     * Data are formatted in columns. Field names are located in the first column
     */
    const dataInCols = 2;

// Class properties

    /**
     * The raw edition of the first line of the uploaded file. To be used for type
     * hinting etc.
     * 
     * @var String 
     */
    public $firstLineRaw = null;

    /**
     * Source encoding
     * 
     * @var CharacterEncoding 
     */
    public $sourceEncoding = CharacterEncoding::Windows_1251;

    /**
     * Target encoding
     * 
     * @var CharacterEncoding 
     */
    public $targetEncoding = CharacterEncoding::UTF_8;

    /**
     * The field name to assign to the auto-generated primary key
     * 
     * @var String 
     */
    public $autoPkName = "autopk_id";

    /**
     * The name of the CSV file to be parsed
     * 
     * @var String 
     */
    public $filename;

    /**
     * An array that will store data rows
     * 
     * @var Array 
     */
    public $rows = array();

    /**
     * An array that will store field names
     * 
     * @var Array
     */
    public $fields = array();

    /**
     * The layout of the data in the source CSV file
     * 
     * @var integer CsvParser::dataInRows|CsvParser::dataInCols 
     */
    public $dataMode = CsvParser::dataInRows;

    /**
     * The character used to delimit fields in the source CSV
     * 
     * @var String 
     */
    public $delimiter = ",";

    /**
     * A flag that indicates whether the first name of the file contains field names
     * 
     * @var Boolean
     */
    public $firstRowFieldNames = true;

    /**
     * The character used to enclose multi-word text sequences that may include
     * the delimiter character
     * 
     * @var String 
     */
    public $enclosure = "\"";

    /**
     * The character used to escape special characters inside the string. Defaults
     * to backslash "\" but is not presently used and cannot be changed.
     * 
     * @var String 
     */
    public $escape = "\\";

    /**
     * The temporary name to assign to the table created from the parsing of the
     * file
     * 
     * @var String 
     */
    public $tmpName;

    /**
     * The number of rows to insert per commit
     * @var Integer 
     */
    public $chunkSize = 5;

    /**
     * The offset from the start of the rows array where the next chunk will be
     * read from
     * 
     * @var Integer
     */
    public $currentOffset = 0;

    /**
     * Database connection
     * 
     * @var mysqli 
     */
    private $dbconn;

    /**
     * Create a new instance of the CsvParser class
     * 
     * @param String $pFilename The filename of a CSV file
     */
    public function __construct($pFilename, $pDbConn = null) {
        $this->filename = $pFilename;
        $this->tmpName = $this->getRandomName();
        if ($pDbConn !== null) {
            $this->dbconn = $pDbConn;
        }
    }

    /**
     * Convert the character encoding of an array or a string
     * 
     * @param Array|String $pStrArray
     * @return Array|String
     */
    public function convert($pStrArray) {

        if (!is_array($pStrArray)) {
            return $pStrArray = iconv($this->sourceEncoding, $this->targetEncoding, $pStrArray);
        } else {
            for ($i = 0; $i < count($pStrArray); $i++) {
                $pStrArray[$i] = iconv($this->sourceEncoding, $this->targetEncoding, $pStrArray[$i]);
            }
            return $pStrArray;
        }
    }

    /**
     * Returns a parsed CSV row
     * 
     * @param filepointer $pFileHandle
     * @return Array|false
     */
    private function getCsv($pFileHandle) {
        $mRow = fgetcsv($pFileHandle, 0, $this->delimiter, $this->enclosure);
        return $mRow;
    }

    /**
     * Parse the data in the file to an object
     * @return void
     */
    public function parseData() {
        if ($this->dataMode === CsvParser::dataInRows) {
            return $this->parseDataInRows();
        } else {
//Not implemented yet
            return;
        }
    }

    /**
     * Parse data if formatted in rows
     * 
     * @return void Loads the data into the fields and rows arrays
     */
    private function parseDataInRows() {
        $mFileHandle = fopen($this->filename, "r");
        
        // Get the first line of the file as a raw string
        $this->firstLineRaw = fgets($mFileHandle);
        
        // Rewind file pointer
        rewind($mFileHandle);

        $mRowNum = 1;
        $mLineNum = 1;
        while (false !== ($mLine = $this->getCsv($mFileHandle))) {
            if (is_array($mLine) && count($mLine) > 1) {
                if ($mRowNum === 1 && $this->firstRowFieldNames === true) {
                    $this->fields = $this->normalize($mLine, true);
                } else
                if ($mRowNum === 1 && $this->firstRowFieldNames === true) {
                    $this->rows[] = $mLine;
                } else {
                    $this->rows[] = $mLine;
                }
                $mRowNum++;
            }
            $mLineNum++;
        }
        fclose($mFileHandle);
    }

    /**
     * Return a unique temporary name
     * 
     * @return String
     */
    private function getRandomName() {
        return "tmp" . time();
    }

    /**
     * Get the create table statement for this table
     * 
     * @return String SQL statement
     */
    public function createTable() {
        $mFieldDefs = array();

// Add system generated primary key to file
        $mFieldDefs[] = sprintf("%s int(11) NOT NULL AUTO_INCREMENT", $this->autoPkName);

// Add fields to table
        foreach ($this->fields as $mField) {
            $mFieldDefs[] = sprintf("%s varchar(255)", $mField);
        }
        $mFieldDefs[] = sprintf("PRIMARY KEY (%s)", $this->autoPkName);
        $mSql = sprintf("CREATE TABLE %s (%s) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;", $this->tmpName, implode(",", $mFieldDefs));

        return $this->dbExec($mSql);
    }

    /**
     * Reset the current offset to the beginning of the currently parsed rows
     * position 0 (zero)
     * 
     * @return Boolean Always returns true
     */
    public function rewindChunks() {
        $this->currentOffset = 0;
        return true;
    }

    /**
     * Insert $this->chunkSize number of records into the database and increment
     * the $this->currentOffset to the start of the next chunk.
     * 
     * @return boolean True on success, false if no more rows to insert
     */
    public function insertChunk() {

        $mStart = $this->currentOffset;
        $mNumRows = count($this->rows);
        $mEnd = ($mStart + $this->chunkSize) > $mNumRows ? $mNumRows : $mStart + $this->chunkSize;

        $mSql = array();
        for ($mRowNum = $mStart; $mRowNum < $mEnd; $mRowNum++) {
            $mRow = $this->rows[$mRowNum];

            $mFieldValues = array();
            $mFieldNames = array();

            foreach ($mRow as $mFieldNameIndex => $mField) {
                $mFieldNames[] = $this->fields[$mFieldNameIndex];
                if ($this->dbconn !== null) {
                    $mFieldValues[] = sprintf("'%s'", mysqli_escape_string($this->dbconn, $mField));
                } else {
                    $mFieldValues[] = sprintf("'%s'", addslashes($mField));
                }
            }

            $mSql[] = sprintf("INSERT INTO %s (%s) VALUES (%s);", $this->tmpName, implode(", ", $mFieldNames), implode(", ", $mFieldValues));
            $this->currentOffset++;
        }

        if (count($mSql) === 0) {
            return false;
        } else {
            return $this->dbExec($mSql);
        }
    }

    /**
     * Delete the current table
     * @return Boolean True on success, false on error
     */
    public function dropTable() {
        return $this->dbExec(sprintf("DROP TABLE %s;", $this->tmpName));
    }

    /**
     * Frees up resources associated with the class
     */
    public function __destruct() {
        if ($this->dbconn != null) {
            mysqli_close($this->dbconn);
        }
    }

    /**
     * Normalizes a string or an array of strings
     * 
     * @param array|string $pInput
     * @param boolean $pUnique Set whether the array should be unique
     * @return array|string
     */
    public function normalize($pInput, $pUnique = false) {
        /*
         * Set the replace elements, keys = search, values = replacement
         */
        $mFrom = array(
            " ",
            ".",
            ";",
            ":",
            "\t",
            "æ",
            "å",
            "ø"
        );

        $mTo = array(
            "_",
            "_",
            "_",
            "_",
            "",
            "ae",
            "aa",
            "oe"
        );

        if (is_array($pInput) === true) {

            foreach ($pInput as $mKey => $mVal) {
                $mVal = str_replace($mFrom, $mTo, strtolower($mVal));
                if (!array_search($mVal, $pInput)) {
                    $pInput[$mKey] = $mVal;
                } else {
                    $mNumberSuffix = 1;
                    while (array_search($mVal . "_" . $mNumberSuffix, $pInput)) {
                        $mNumberSuffix++;
                    };
                    $pInput[$mKey] = $mVal . "_" . $mNumberSuffix;
                }
            }
            return $pInput;
        } else {
            // do something
            $pInput = str_replace($mFrom, $mTo, strtolower($pInput));
            return $pInput;
        }
    }

    /**
     * Execute database statement
     * 
     * @param Array|String $pSql SQL statement
     * @return Boolean|String True on success, false on error
     */
    private function dbExec($pSql) {

        $pSql = CsvParser::convert($pSql);

        if (!is_array($pSql)) {
            $pSql = array($pSql);
        }

        if ($this->dbconn !== null) {

            $s = true;
            mysqli_query($this->dbconn, "SET NAMES 'utf8';");
            foreach ($pSql as $mSqlStatement) {
                mysqli_query($this->dbconn, $mSqlStatement);

                if (mysqli_errno($this->dbconn)) {
                    echo mysqli_error($this->dbconn);
                    $s = false;
                }
            }
            return $s;
        } else {
            return implode("\n", $pSql);
        }
    }

    /**
     * The delimiter character to use when parsing the file
     * 
     * @param string $pDelimiterName One of "comma", "semicolon" or "tab"
     * @return void
     */
    public function setDelimiter($pDelimiterName = "comma") {
        switch ($pDelimiterName) {
            case "comma":
                $this->delimiter = ",";
                break;
            case "semicolon":
                $this->delimiter = ";";
                break;
            case "tab":
                $this->delimiter = "\t";
        }
        return;
    }

}

?>
