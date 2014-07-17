<?php

/**
 * This class defines methods to interact with a MySQL table. It is first and
 * foremostly intended to be extended by classes defining the fields of tables
 * etc.
 */
class MySQLTable {

    /**
     * The SQL select statement including any external joins etc.
     * @var string 
     */
    protected $_select = null;

    /**
     * The name of the table column that is the primary key of the dataset
     * @var string 
     */
    protected $_pk = null;

    /**
     * The name of the table
     * @var string  
     */
    protected $_table = null;

    /**
     * An array of where clause elements
     * @var Array 
     */
    protected $_where = array();

    /**
     * An array that contains names of mandatory fields
     * @var array 
     */
    protected $_mandatory = array();

    /**
     * Creates a new table object
     * @param array $pValues An associative array with fieldnames as keys and
     * field values as entries.
     */
    public function __construct($pTable,
            $pPk,
            $pValues = null) {

        // Assign the primary key column
        $this->_pk = $pPk;

        // Assign the table name
        $this->_table = $pTable;

        // If values are supplied, initialize the object values
        if ($pValues !== null) {
            $mFields = $this->_getFieldsAssoc();
            foreach ($pValues as $mKey => $mVal) {
                if (array_key_exists($mKey,
                                $mFields)) {
                    $this->$mKey = $mVal;
                }
            }
        }

        // Setup thet basic SQL select statement
        $this->_select = sprintf("SELECT SQL_CALC_FOUND_ROWS %s FROM %s",
                "t." . implode(", t.",
                        $this->_getFieldNames()),
                $this->_table . " t"
        );
    }

    /**
     * Adds a single field name (string) or an array of field names to the list
     * of mandatory fields in a class.
     * 
     * @param string|array $pFieldNames
     * @return void
     */
    public function _getMandatory($pFieldNames) {
        if (is_string($pFieldNames)) {
            $this->_mandatory[] = $pFieldNames;
        } elseif (is_array($pFieldNames)) {
            $this->_mandatory = array_merge($this->_mandatory,
                    $pFieldNames);
        }
        return;
    }

    /**
     * Select a set of objects and remap names for recid, label and value to
     * work smoothly with jQuery.autocomplete and w2ui grids
     * @param string $pWhere
     * @param string $pOrderBy
     * @param int $pLimit
     * @param int $pOffset
     * @param W2UIRetObj $pRetObj
     * @return bool True on sucess, false on error
     */
    public function selectMap($pWhere = null,
            $pOrderBy = null,
            $pLimit = null,
            $pOffset = null,
            &$pRetObj = null,
            $pLabelField = null,
            $pValueField = null,
            $pRecIDField = null
    ) {

        $mRes = $this->select($pWhere,
                $pOrderBy,
                $pLimit,
                $pOffset,
                $pRetObj);
        $mReturn = array();
        while ($mRes !== false && $mObj = mysqli_fetch_object($mRes)) {
            if (isset($mObj->$pLabelField)) {
                $mObj->label = $mObj->$pLabelField;
            }
            if (isset($mObj->$pValueField)) {
                $mObj->value = $mObj->$pValueField;
            }
            if (isset($mObj->$pRecIDField)) {
                $mObj->recid = $mObj->$pRecIDField;
            }
            $mReturn[] = $mObj;
        }
        $pRetObj->records = $mReturn;
        return $pRetObj->status;
    }

    /**
     * Select from the table
     * @param String $pWhere Only the attr=value and logical operators, e.g. id=1 OR id=2
     * @param String $pOrderBy Field name to sort by
     * @param Number $pLimit Number of rows to retrieve
     * @param Number $pOffset Offset from start
     * @param W2UIRetObj $pRetObj Offset from start
     * @return Array An array of objects retrieved from the search
     */
    public function select($pWhere = null,
            $pOrderBy = null,
            $pLimit = null,
            $pOffset = null,
            &$pRetObj = null) {

        $mSql = $this->_select;

        if ($pWhere != null) {
            $mSql .= " WHERE $pWhere";
        }

        if ($pOrderBy != null) {
            $mSql .= " ORDER BY $pOrderBy";
        }

        if (!is_null($pOffset) && !is_null($pLimit)) {
            $mSql .= " LIMIT $pLimit OFFSET $pOffset";
        }

        $mReturn = Db::query($mSql);
        if (!$pRetObj == null) {

            $mCount = Db::getLastCount();

            $pRetObj->total = $mCount;

            $pRetObj->addMsg($mSql);
            if ($mReturn) {
                $pRetObj->setSuccess();
            } else {
                $pRetObj->setFailure();
            }
        }

        return $mReturn;
    }

    /**
     * This method returns an SQL insert statement
     * @param iRetObj $pRetObj Return object
     * @return boolean True on success, false on error
     */
    public function insert(&$pRetObj = null) {

        if ($this->_checkMandatory()) {
            $mVars = $this->_getFieldsAssoc();
            $mFields = array();
            $mValues = array();

            foreach ($mVars as $mKey => $mVal) {
                if ($mVal != null) {
                    if ($mKey != $this->_pk) {
                        $mFields[] = $mKey;
                        $mValues[] = "'" . $mVal . "'";
                    }
                }
            }

            $mSql = sprintf("INSERT INTO %s (%s) VALUES (%s);",
                    $this->_table,
                    implode(",",
                            $mFields),
                    implode(",",
                            $mValues));

            $mReturn = Db::query($mSql);
            if ($pRetObj !== null) {
                if (!$mReturn) {
                    $pRetObj->setFailure("Insert failed");
                } else {
                    $pRetObj->setSuccess();
                }
            }
        } else {
            $mReturn = false;
        }

        if (!$pRetObj == null) {
            if ($mReturn) {
                $pRetObj->setSuccess();
            } else {
                $pRetObj->setFailure();
            }
        }
        return $mReturn;
    }

    /**
     * This method returns an SQL insert statement
     * @return boolean True on success, false on error
     */
    public function upsert(&$pRetObj = null) {
        if ($this->_checkMandatory()) {
            $mVars = $this->_getFieldsAssoc();
            $mFields = array();
            $mValues = array();
            $mUpdateClause = array();

            foreach ($mVars as $mKey => $mVal) {
                if ($mVal != null) {
                    if ($mKey != $this->_pk) {
                        $mFields[] = $mKey;
                        $mValues[] = "'" . $mVal . "'";
                        $mUpdateClause[] = $mKey . "='" . $mVal . "'";
                    }
                }
            }

            $mSql = sprintf("INSERT INTO %s (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s",
                    $this->_table,
                    implode(",",
                            $mFields),
                    implode(",",
                            $mValues),
                    implode(",",
                            $mUpdateClause));

            $mReturn = Db::query($mSql);
        } else {
            $mReturn = false;
        }

        if (!$pRetObj == null) {
            if ($mReturn) {
                $pRetObj->setSuccess();
            } else {
                $pRetObj->setFailure();
            }
        }
        return $mReturn;
    }

    /**
     * Get the SQL required to perform an update on an object
     * @param W2UIRetObj $pRetObj Description
     * @return void
     */
    function update($pRetObj = null) {
        $mVars = $this->_getFieldsAssoc();
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
        if (count($mUpdateSets) >= 1) {
            $mSQL = sprintf("UPDATE %s SET %s WHERE %s = '%s';",
                    $this->_table,
                    implode(",",
                            $mUpdateSets),
                    $this->_pk,
                    $mPKValue);
            $mReturn = Db::query($mSQL);
            if (!$pRetObj == null) {
                if ($mReturn) {
                    $pRetObj->setSuccess();
                } else {
                    $pRetObj->setFailure("Database error, check log file.");
                }
            }
        } else {
            $pRetObj->setFailure("Error, no input fields specified");
        }
        return $mReturn;
    }

    /**
     * Get the SQL required to delete an object
     * @param W2UIRetObj $pRetObj
     * @return bool|mysqli SQL Statement
     */
    function delete($pRetObj = null) {
        $mVars = $this->_getFieldsAssoc();
        $mSql = sprintf("DELETE FROM %s WHERE %s = '%s';",
                $this->_table,
                $this->_pk,
                $mVars[$this->_pk]);
        $mReturn = Db::query($mSql);
        if (!$pRetObj == null) {
            if ($mReturn === false) {
                $pRetObj->setFailure("SQL error, please refer to log for details.");
            }
        }
        return $mReturn;
    }

    /**
     * Return the field names of the current class
     * @return array
     */
    public function _getFieldNames() {
        $mReturn = array();
        $mFields = $this->_getFieldsAssoc();
        foreach ($mFields as $mFieldName => $mFieldValue) {
            if ($mFieldName === "recid") {
                $mFieldName = $this->_pk . " " . $mFieldName;
            }
            $mReturn[] = $mFieldName;
        }
        return $mReturn;
    }

    /**
     * Get the fields in the current table excluding internal fields
     * @return array Array of field names (string)
     */
    public function _getFieldsAssoc() {
        $mFields = array();
        foreach (get_object_vars($this) as $mKey => $mVal) {
            if (!startsWith($mKey,
                            "_")) {
                $mFields[$mKey] = $mVal;
            }
        }
        return $mFields;
    }

    /**
     * Check if all mandatory fields are specified
     * @return boolean True if all mandatory fields are set, false otherwise
     */
    public function _checkMandatory() {
        $mFields = $this->_getFieldsAssoc();
        foreach ($this->_mandatory as $mMField) {
            if ($mFields[$mMField] === null) {
                return false;
            }
        }
        return true;
    }

}
