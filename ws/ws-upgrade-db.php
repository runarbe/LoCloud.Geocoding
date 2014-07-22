<?php

require_once('../functions.php');

/**
 * Description of WsUpgradeDb
 *
 * @author runarbe
 */
class WsUpgradeDb extends GcWebService implements iWebService {

    const MatchTables = "__allMatchTables__";
    const DatasourceTables = "__allDatasourceTables__";

    protected function _execute() {

        /*
         *  Upgrade from 1.x.x to 1.6.4
         */
        $this->addColumns("meta_dbsearch",
                array(
            "sch_apikey" =>
            "VARCHAR(200) NULL DEFAULT NULL"));

        $this->changeColumns("meta_usr",
                array(
            "usr" => "usr VARCHAR(250) NOT NULL",
            "pwd" => "pwd VARCHAR(20) NOT NULL"
        ));

        $this->dropColumns(WsUpgradeDb::MatchTables,
                array("gc_timestamp",
            "gc_puri",
            "gc_dbsearch_id",
            "gc_dbsearch_db",
            "gc_full_geom",
            "gc_name2"
        ));

        $this->changeColumns(WsUpgradeDb::MatchTables,
                array(
            "gc_probability" => "gc_probability INT(11) NULL DEFAULT NULL"
        ));

        $this->addColumns(WsUpgradeDb::MatchTables,
                array(
            "gc_confidence" => "INT(11) NULL DEFAULT NULL",
            "gc_mapresolution" => "FLOAT(11) NULL DEFAULT NULL"
        ));

        $this->dropIndices(WsUpgradeDb::MatchTables,
                array(
            'idx_match_gc_dbsearch_db'
        ));

        // Output result
        $this->_result->echoJson();
    }

    private function addColumns($pTableName,
            $pColumns) {
        foreach ($this->_getTableNames($pTableName) as $mTableName) {
            foreach ($pColumns as $mColumnName => $mColumnDefinition) {
                $this->_addColumn($mTableName,
                        $mColumnName,
                        $mColumnDefinition);
            }
        }
        return;
    }

    private function _addColumn($pTableName,
            $pColumnName,
            $pColumnDefinition) {
        if (!$this->_columnExists($pTableName,
                        $pColumnName)) {
            $mSql = sprintf("ALTER TABLE %s ADD COLUMN %s %s",
                    $pTableName,
                    $pColumnName,
                    $pColumnDefinition);
            if (Db::execute($mSql) === true) {
                $this->_result->setFailure(ErrorMsgs::dbErrorUpdateCheckLog,
                        $pTableName . "/" . $pColumnName);
            }
        }
        return;
    }

    private function dropIndices($pTableName,
            $pIndexNames) {
        foreach ($this->_getTableNames($pTableName) as $mTableName) {
            foreach ($pIndexNames as $mIndexName) {
                $this->_dropIndex($mTableName,
                        $mIndexName);
            }
        }
        return;
    }

    private function _dropIndex($pTableName,
            $pIndexName) {
        if ($this->_indexExists($pTableName,
                        $pIndexName)) {
            $mSql = sprintf("ALTER TABLE %s DROP INDEX %s;",
                    $pTableName,
                    $pIndexName);
            if (Db::execute($mSql) === false) {
                $this->_result->setFailure(ErrorMsgs::database,
                        $pTableName . "/" . $pIndexName);
            }
        }
        return;
    }

    private function changeColumns($pTableName,
            $pColumns) {

        foreach ($this->_getTableNames($pTableName) as $mTableName) {
            foreach ($pColumns as $mColumnName => $mColumnDefinition) {
                $this->_changeColumn($mTableName,
                        $mColumnName,
                        $mColumnDefinition);
            }
        }
        return;
    }

    private function _changeColumn($pTableName,
            $pColumnName,
            $pColumnDefinition) {
        if (!$this->_columnExists($pTableName,
                        $pColumnName)) {
            $mSql = sprintf("ALTER TABLE %s CHANGE COLUMN %s %s",
                    $pTableName,
                    $pColumnName,
                    $pColumnDefinition);

            if (Db::execute($mSql) === false) {
                $this->_result->setFailure(ErrorMsgs::dbErrorUpdateCheckLog,
                        $pTableName . "/" . $pColumnName);
            }
        }
        return;
    }

    /**
     * Get all table names to be used in an operation
     * @param String|WsUpgradeDB::const $pTableName Either a valid table name or one of the string constants of the WsUpgradeDB class
     * @return Array Array of table names
     */
    private function _getTableNames($pTableName) {
        switch ($pTableName) {
            case WsUpgradeDb::MatchTables:
                $mTableNames = $this->_getDatasourceMatchTableNames();
                $mTableNames[] = "meta_match_template";
                break;
            case WsUpgradeDb::DatasourceTables:
                $mTableNames = $this->_getDatasourceTableNames();
                break;
            default:
                $mTableNames = array($pTableName);
                break;
        }
        return $mTableNames;
    }

    /**
     * Drops an array of named columns from a table
     * @param String $pTableName
     * @param Array $pColumnNames Array of column names
     * @return void
     */
    private function dropColumns($pTableName,
            $pColumnNames) {


        foreach ($this->_getTableNames($pTableName) as $mTableName) {

            foreach ($pColumnNames as $pColumnName) {
                $this->_dropColumn($mTableName,
                        $pColumnName);
            }
        } return;
    }

    /**
     * Drops a column from a table
     * @param String $pTableName
     * @param String $pColumnName
     * @return void
     */
    private function _dropColumn($pTableName,
            $pColumnName) {
        logIt($pTableName);
        if ($this->_columnExists($pTableName,
                        $pColumnName)) {
            logIt('Attempting to delete: ' . $pColumnName);
            $mSql = sprintf('ALTER TABLE %s DROP COLUMN %s;',
                    $pTableName,
                    $pColumnName);
            if (Db::execute($mSql) === false) {
                $this->_result->addData(ErrorMsgs::database,
                        $pTableName . "/" . $pColumnName);
            }
        }
        return;
    }

    /**
     * Checks if a column exists
     * @param String $pTableName
     * @param String $pColumnName
     * @return boolean
     */
    private function _columnExists($pTableName,
            $pColumnName) {
        $mSql = sprintf("SELECT exists (SELECT * FROM information_schema.columns WHERE table_schema='%s' AND table_name = '%s' and column_name = '%s')",
                LgmsConfig::db,
                $pTableName,
                $pColumnName);
        $mExists = Db::executeScalar($mSql);
        if ($mExists == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if a table exists
     * @param String $pTableName
     * @return boolean
     */
    private function _tableExists($pTableName) {
        $mSql = sprintf("SELECT exists (SELECT * FROM information_schema.tables WHERE table_schema='%s' AND table_name = '%s')",
                LgmsConfig::db,
                $pTableName);
        $mExists = Db::executeScalar($mSql);
        if ($mExists == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if an index exists
     * @param String $pTableName
     * @param String $pIndexName
     * @return boolean
     */
    private function _indexExists($pTableName,
            $pIndexName) {
        $mSql = sprintf("SELECT exists (SELECT * FROM information_schema.statistics WHERE table_schema = '%s' AND table_name = '%s' AND index_name = '%s')",
                LgmsConfig::db,
                $pTableName,
                $pIndexName);
        $mExists = Db::executeScalar($mSql);
        if ($mExists == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Execute SQL for the match_template and each of the datasource specific match tables
     * @param String $pSql
     */
    private function forEachMatchTable($pSql) {
        $mTables = array("meta_match_template");
        $mDatasets = Db::query("SELECT DISTINCT ds_table FROM meta_datasources");
        while (null !== ($mRow = mysqli_fetch_array($mDatasets))) {
            $mTables[] = $mRow["ds_table"] . "_match";
        }
        foreach ($mTables as $mTable) {
            $mSql = sprintf($pSql,
                    $mTable);
            Db::query_multi($mSql);
        }
    }

    private function _getDatasourceTableNames() {
        $mTableNames = array();
        $mDatasets = Db::query("SELECT DISTINCT ds_table FROM meta_datasources");
        while (null !== ($mRow = mysqli_fetch_array($mDatasets))) {
            $mTableNames[] = $mRow["ds_table"];
        }
        return $mTableNames;
    }

    private function getTempTableNames() {
        $mTableNames = array();
        $mDatasets = Db::query("SELECT table_name FROM information_schema.tables WHERE table_schema='%s' AND table_name LIKE 'tmp%'");
        while (null !== ($mRow = mysqli_fetch_array($mDatasets))) {
            $mTableNames[] = $mRow["table_name"];
        }
        return $mTableNames;
    }

    private function _getDatasourceMatchTableNames() {
        $mTableNames = array();
        $mDatasets = Db::query("SELECT DISTINCT ds_table FROM meta_datasources");
        while (null !== ($mRow = mysqli_fetch_array($mDatasets))) {
            $mTableNames[] = $mRow["ds_table"] . "_match";
        }
        return $mTableNames;
    }

    private function forEachDatasourceTable($pSql) {
        $mTables = array();
        $mDatasets = Db::query("SELECT DISTINCT ds_table FROM meta_datasources");
        while (null !== ($mRow = mysqli_fetch_array($mDatasets))) {
            $mTables[] = $mRow["ds_table"];
        }
        foreach ($mTables as $mTable) {
            $mSql = sprintf($pSql,
                    $mTable);
            Db::query_multi($mSql);
        }
    }

    public static function getInstance() {
        return new WsUpgradeDb();
    }

}

WsUpgradeDb::getInstance()->run(true);
