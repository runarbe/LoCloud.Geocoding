<?php

/**
 * Class to parse CSV-files into SQL databases 
 * @author Stein Runar Bergheim
 * @version 1.0
 * @copyright (c) 2013, Asplan Viak Internet AS
 * @see http://www.avinet.no
 * @uses PHP5 >= v5.0.2
 */
class TextFileParser {

    /**
     * Name of the table to generate from the parsed text file
     * @var String 
     */
    protected $tableName;

    /**
     * File handle
     * @var Object
     */
    protected $fileHandle;

    /**
     * Current table row being processed
     * @var Integer
     */
    protected $currentRow = 0;

    /**
     * Current table column being processed
     * @var Integer
     */
    protected $currentCol = 0;

    /**
     * Internal batch counter
     * @var integer
     */
    private $batchCounter = 0;

    /**
     * Number of records to process in one batch
     * @var integer
     */
    public $batchSize = 50;

    /**
     * Number of the header row
     * @var integer
     */
    public $headerRow = 1;

    /**
     * The character used to separate fields in the source data
     * @todo Description Only relevant if TextFileType:FixedWidth
     * @var string
     */
    public $sepChar = ",";

    /**
     * Number of rows to skip from top of input file
     * @var integer
     */
    public $skipRows = 0;

    /**
     * Character encoding of input file
     * @var string
     */
    public $charEncoding = "UTF-8";

    /**
     * Value that determine whether the input file is fixed width or character separated
     * @var TextFileType 
     */
    public $textFileType = TextFileType::Delimited;

    /**
     * Set of field definitions
     * @var FieldDefSet 
     */
    public $fieldDefSet = null;

    /**
     * Null-data value
     * @var string 
     */
    public $nullDataValue = "";

    /**
     * Character used to separate fields in a delimited text-file
     * @var string 
     */
    public $textQualifier = "\"";

    /**
     * Array to store error and information messages generated during execution
     * @var array 
     */
    private $messageLog = array();

    /**
     * Field names to be used in the SQL insert statements
     * @var String Comma separated field names
     */
    private $sqlInsertFields = "";

    /**
     * Constructor
     * @param TextFileType $pTextFileType The type of file, delimited or fixed width
     * @param String $pFileName The name of the file without any path. Assumed to be present in ./upload directory
     */
    public function __construct($pTableName, $pTextFileType = TextFileType::Delimited, $pFileName = null) {
        $this->tableName = $pTableName;
        $this->textFileType = $pTextFileType;
        $this->fieldDefSet = new FieldDefSet();

        if ($pFileName != null) {
            $this->initFile($pFileName);
        }
    }

    /**
     * Sets the name of the table to be generated from parsing text file to SQL
     * @param String $pTableName Name of table
     */
    public function setTableName($pTableName) {
        $this->tableName = $pTableName;
    }

    /**
     * Reads one line from the currently open starting from the current file-pointer location
     * @return array
     */
    function getCsvLine() {
        return fgetcsv($this->fileHandle, 0, $this->sepChar, $this->textQualifier);
    }

    /**
     * Reads the number of lines defined as the batchSize and returns the info as a multi-dimensional array
     * @return array 2-dimensional array of field values
     */
    function parseChunk() {
        $retVal = array();
        if ($this->fileHandle) {
            switch ($this->textFileType) {
                case TextFileType::FixedWidth :
                    while (($mLine = fgets($this->fileHandle)) && ($this->batchCounter < $this->batchSize)) {
                        if ($this->currentRow >= $this->skipRows) {
                            $retVal .= $this->parseLine($mLine);
                            $this->batchCounter++;
                        }
                        $this->currentRow++;
                    }
                    $this->batchCounter = 0;
                case TextFileType::Delimited:
                    while ($mLine = $this->getCsvLine()) {
                        $retVal[] = $mLine;
                    };
            }
        } else {
            $this->logMessage(TextFileErrorMsg::FileIOError);
        }
        return $retVal;
    }

    private function getNormalizedValue($pFieldValue, $pTextFieldDataType) {
        $pFieldValue = trim($this->strip4byte($pFieldValue));

        if ($pFieldValue == $this->nullDataValue) {
            return "null";
        } else {
            switch ($pTextFieldDataType) {
                case TextFieldDataType::Integer :
                    return $pFieldValue;
                case TextFieldDataType::String :
                    return "'" . $pFieldValue . "'";
                default:
                    return $pFieldValue;
            }
        }
    }

    private function parseLine($pLine) {
        $mRetVal = array();
        switch ($this->textFileType) {
            case TextFileType::FixedWidth :
                /* @var $mFieldDef FieldDef */
                foreach ($this->fieldDefSet->FieldDefs as $mFieldDef) {
                    $mRetVal[] = $this->getNormalizedValue(substr($pLine, $mFieldDef->ZeroBasedStart, $mFieldDef->Length), $mFieldDef->DataType);
                }
            case TextFileType::Delimited :
                $mRetVal = explode($this->sepChar, $pLine);
                $mNumFields = count($mRetVal);
                for ($i = 0; $i < $mNumFields; $i++) {
                    $mTmpVal = $mRetVal[$i];
                    $mTmpVal = trim($mTmpVal);

                    $j = $i + 1;

                    $p1 = 0;
                    $p2 = strlen($mTmpVal) - 1;

                    if (substr($mTmpVal, 0, 1) == $this->textQualifier) {
                        $p1++;
                    }

                    if (substr($mTmpVal, -1, 1) == $this->textQualifier) {
                        $e1--;
                    }

                    $mTmpVal = substr($mTmpVal, $p1, $p2 - $p1);
                    $mTmpVal = trim($mTmpVal);

                    $mRetVal[$i] = $mTmpVal;
                }
        }
        return $mRetVal;
    }

    function addFieldDef($pFieldName, $pStartOffset, $pEndOffset, $pDataType) {
        if ($this->textFileType == TextFileType::FixedWidth) {
            $tmpFieldDef = new FieldDef($pFieldName, $pStartOffset, $pEndOffset, $pDataType);
            $this->fieldDefSet->addFieldDef($tmpFieldDef);
            return true;
        } else {
            return false;
        }
    }

    function __destruct() {
        if ($this->fileHandle) {
            if (!fclose($this->fileHandle)) {
                die('Could not close file');
            };
        }
    }

    private function logMessage($pMsg) {
        $this->messageLog[] = var_export($pMsg, true);
    }

    public function getLog() {
        return implode("\n", $this->messageLog);
    }

    function setNullDataValue($pNullDataValue) {
        $this->nullDataValue = $pNullDataValue;
    }

    function setBatchSize($pBatchSize) {
        $this->batchSize = $pBatchSize;
    }

    private function closeFile() {
        if ($this->fileHandle) {
            fclose($this->fileHandle);
        }
        return true;
    }

    public function initFile($pFileName) {
        // Close any existing open file
        $this->closeFile();

        // Reset all internal counters
        $this->batchCounter = 0;
        $this->currentRow = 0;
        $this->currentCol = 0;

        // Open the new file
        if (!$this->fileHandle = fopen($pFileName, "r")) {
            die('error opening file');
        }

        // Initialize fields
        if ($this->fileHandle && $this->textFileType == TextFileType::Delimited) {
            $this->fieldDefSet = new FieldDefSet();
            $this->initFieldDefs();
        }
        
        // Seek to start of data
        $this->seekToStartOfData();
        
    }

    function setSepChar($pSepChar) {
        $this->sepChar = $pSepChar;
    }

    function setSkipRows($pSkipRows) {
        $this->skipRows = $pSkipRows;
    }

    function setHeaderRow($pHeaderRow) {
        $this->headerRow = $pHeaderRow;
    }

    /**
     * Read the line that contains column titles/field names from the text file and add field definitions to the class.
     */
    function initFieldDefs() {
        $mTmpFields = $this->getLineNumber($this->headerRow);
        $mSqlInsertFields = array();

        /* @var $mTmpField String */
        foreach ($mTmpFields as $mTmpField) {

            $mTmpField = strtolower($mTmpField);
            $mFieldDef = new FieldDef($mTmpField);
            $this->fieldDefSet->addFieldDef($mFieldDef);
            $mSqlInsertFields[] = $mTmpField;
        }

        $this->sqlInsertFields = implode(", ", $mSqlInsertFields);
    }

    function setTextQualifier($pTextQualifier) {
        $this->textQualifier = $pTextQualifier;
    }

    function getLineNumber($pLineNumber) {
        $mLineNumber = 0;
        $mFilePos = ftell($this->fileHandle);
        rewind($this->fileHandle);
        $mLine = null;
        while (($mLine = $this->getCsvLine()) !== FALSE) {
            if ($mLineNumber == ($pLineNumber - 1)) {
                break;
            }
            $mLineNumber++;
        }
        fseek($this->fileHandle, $mFilePos, 'SEEK_SET');
        return $mLine;
    }

    function seekToStartOfData() {
        $mLineNumber = 0;
        rewind($this->fileHandle);
        $mLine = null;
        while (($mLine = $this->getCsvLine()) !== FALSE) {
            if ($mLineNumber == ($this->headerRow - 1)) {
                break;
            }
            $mLineNumber++;
        }
    }

    public function getSQLCreateTable() {
        $mSQL = "CREATE TABLE " . $this->tableName . "(";
        $mFields = array();
        $mFields[] = "gcuid int(11) NOT NULL AUTO_INCREMENT";
        /* @var $mFieldDef FieldDef */
        foreach ($this->fieldDefSet->FieldDefs as $mFieldDef) {
            $mFields[] = $mFieldDef->FieldName . " " . $mFieldDef->DataType . "";
        }
        $mFields[] = "PRIMARY KEY (gcuid)";
        $mFields[] = "UNIQUE KEY idx_" . $this->tableName . "_pk (gcuid)";

        $mSQL .= (implode(",", $mFields)) . ");";
        return $mSQL;
    }

    /**
     * Get an SQL insert statement for the current row in the file, advance to the next row.
     * @return String SQL insert statement
     */
    public function getSQLInsertRow() {

        $mValues = $this->getCsvLine();
        if (!false && is_array($mValues)) {

            $mValues = "'" . implode("', '", $mValues) . "'";

            /**
             *  Create SQL insert statement
             */
            $mSQL = sprintf("INSERT INTO %s (%s) VALUES (%s);", $this->tableName, $this->sqlInsertFields, $mValues
            );
            return $mSQL;
        } else {
            return false;
        }
    }

    /**
     * Strip 4-byte characters from strings. 
     * @param String $pString String that may contain 4-byte characters
     * @return String String without 4-byte characters 
     * @todo This function is needed due to a common constraint in recent versions of MySQL that do not permit UTF-8 with more than 3 bytes per character.
     */
    private function strip4byte($pString) {
        $char_array = preg_split('/(?<!^)(?!$)/u', $pString);
        for ($x = 0; $x < sizeof($char_array); $x++) {
            if (strlen($char_array[$x]) > 3) {
                $char_array[$x] = "???????????";
            }
        }
        return implode($char_array, "");
    }

}
?>