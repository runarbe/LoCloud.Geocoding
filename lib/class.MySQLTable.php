<?php

/**
 * This class defines methods to interact with a MySQL table. It is first and
 * foremostly intended to be extended by classes defining the fields of tables
 * etc.
 */
class MySQLTable {

    /**
     * The name of the table column that is the primary key of the dataset
     * @var string 
     */
    public $_pk = null;

    /**
     * The name of the table
     * @var string
     */
    public $_table = null;

    /**
     * An array that contains names of mandatory fields
     * @var array 
     */
    public $_mandatory = array();

    /**
     * Database connection
     * @var mysqli_connection
     */
    private $_dbconn;

    /**
     * Creates a new table object
     * @param array $pValues An associative array with fieldnames as keys and
     * field values as entries.
     */
    public function __construct($pTable, $pPk, $pDbConn = null, $pValues = null) {

        // Assign the primary key column
        $this->_pk = $pPk;

        // Assign the table name
        $this->_table = $pTable;

        // If a database connection is present in the parameters, set it
        if ($pDbConn !== null) {
            $this->_dbconn = $pDbConn;
        }

        // If values are supplied, initialize the object values
        if ($pValues !== null) {
            $mFields = $this->getFields();
            foreach ($pValues as $mKey => $mVal) {
                if (array_key_exists($mKey, $mFields)) {
                    $this->$mKey = $mVal;
                }
            }
        }
    }

    /**
     * Adds a single field name (string) or an array of field names to the list
     * of mandatory fields in a class.
     * 
     * @param string|array $pFieldNames
     * @return void
     */
    public function setMandatory($pFieldNames) {
        if (is_string($pFieldNames)) {
            $this->_mandatory[] = $pFieldNames;
        } elseif (is_array($pFieldNames)) {
            $this->_mandatory = array_merge($this->_mandatory, $pFieldNames);
        }
        return;
    }

    /**
     * This method returns an SQL insert statement
     * @return String
     */
    public function Insert() {
        if ($this->checkMandatory()) {
            $mVars = $this->getFields();
            $mFields = array();
            $mValues = array();
            foreach ($mVars as $mKey => $mVal) {
                if ($mVal !== null) {
                    if ($mKey != $this->_pk) {
                        $mFields[] = $mKey;
                        $mValues[] = "'" . $mVal . "'";
                    }
                }
            }
            $mSql = sprintf("INSERT INTO %s (%s) VALUES (%s);", $this->_table, implode(",", $mFields), implode(",", $mValues));
            return $this->execDb($mSql);
        } else {
            return false;
        }
    }

    /**
     * Get the SQL required to perform an update on an object
     * @return string SQL statement
     */
    function Update() {
        $mVars = $this->getFields();
        $mUpdateSets = array();
        $mPKValue = null;
        foreach ($mVars as $mKey => $mVal) {
            if ($mVal !== null) {
                if ($mKey === $this->_pk) {
                    $mPKValue = $mVal;
                } else {
                    $mUpdateSets[] = $mKey . "='" . $mVal . "'";
                }
            }
        }
        $mSQL = sprintf("UPDATE %s SET (%s) WHERE %s = '%s';", $this->_table, implode(",", $mUpdateSets), $this->_pk, $mPKValue);
        return $this->execDb($mSQL);
    }

    /**
     * Get the SQL required to delete an object
     * @return string SQL Statement
     */
    function Delete() {
        $mVars = $this->getFields();
        $mSql = sprintf("DELETE FROM %s WHERE %s = '%s';", $this->_table, $this->_pk, $mVars[$this->_pk]);
        return $this->execDb($mSql);
    }

    /**
     * Get the fields in the current table
     * @return array Array of field names (string)
     */
    public function getFields() {
        $mFields = array();
        foreach (get_object_vars($this) as $mKey => $mVal) {
            if (substr($mKey, 0, 1) !== "_") {
                $mFields[$mKey] = $mVal;
            }
        }
        return $mFields;
    }

    /**
     * Execute an SQL statement
     * @param string $pSql
     * @return boolean|string True on success, false on error, string if dbconn is not set.
     */
    private function execDb($pSql) {
        if ($this->_dbconn !== null) {
            mysqli_query($this->_dbconn, $pSql);
            if (mysqli_errno($this->_dbconn)) {
                return false;
            } else {
                return true;
            }
        } else {
            return $pSql;
        }
    }

    /**
     * Check if all mandatory fields are specified
     * @return boolean True if all mandatory fields are set, false otherwise
     */
    public function checkMandatory() {
        $mFields = $this->getFields();
        foreach ($this->_mandatory as $mMField) {
            if ($mFields[$mMField] === null) {
                return false;
            }
        }
        return true;
    }

}

?>
