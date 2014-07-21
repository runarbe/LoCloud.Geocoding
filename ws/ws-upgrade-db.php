<?php

require_once('../functions.php');

/**
 * Description of WsUpgradeDb
 *
 * @author runarbe
 */
class WsUpgradeDb extends GcWebService implements iWebService {

    protected function _execute() {
        // Upgrade from 1.x.x to 1.6.4
        $mSql = "ALTER TABLE meta_dbsearch 
            ADD COLUMN `sch_apikey` VARCHAR(200) NULL DEFAULT NULL AFTER `sch_webservice`;            
            ALTER TABLE meta_usr 
            CHANGE COLUMN `usr` `usr` VARCHAR(250) NOT NULL ,
            CHANGE COLUMN `pwd` `pwd` VARCHAR(20) NOT NULL ;";
        Db::query_multi($mSql);

        $this->forEachMatchTable("ALTER TABLE %s 
            DROP COLUMN `gc_name2`,
            ADD COLUMN `gc_confidence` INT(11) NOT NULL DEFAULT '-1' AFTER `gc_probability`,
            ADD COLUMN `gc_mapresolution` FLOAT(11) NOT NULL DEFAULT '-1' AFTER `gc_confidence`;");
        
        // Output result
        $this->_result->echoJson();
    }

    private function forEachMatchTable($pSql) {
        $mTables = array("meta_match_template");
        $mDatasets = Db::query("SELECT DISTINCT ds_table FROM meta_datasources");
        while (null !== ($mRow = mysqli_fetch_array($mDatasets))) {
            $mTables[] = $mRow["ds_table"]."_match";
        }
        foreach ($mTables as $mTable) {
            $mSql = sprintf($pSql, $mTable);
            Db::query_multi($mSql);
        }
    }

    private function forEachDatasourceTable($pSql) {
        $mTables = array();
        $mDatasets = Db::query("SELECT DISTINCT ds_table FROM meta_datasources");
        while (null !== ($mRow = mysqli_fetch_array($mDatasets))) {
            $mTables[] = $mRow["ds_table"];
        }
        foreach ($mTables as $mTable) {
            $mSql = sprintf($pSql, $mTable);
            Db::query_multi($mSql);
        }
    }
    
    public static function getInstance() {
        return new WsUpgradeDb();
    }

}

WsUpgradeDb::getInstance()->run(true);
