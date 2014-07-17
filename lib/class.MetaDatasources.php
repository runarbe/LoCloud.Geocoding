<?php

class MetaDatasources extends MySQLTable {

    public $id = null;
    public $ds_title = null;
    public $ds_col_pk = null;
    public $ds_col_name = null;
    public $ds_col_x = null;
    public $ds_col_y = null;
    public $ds_srs = null;
    public $ds_table = null;
    public $ds_col_cat = null;
    public $ds_col_adm0 = null;
    public $ds_col_adm1 = null;
    public $ds_coord_prec = null;
    public $ds_col_image = null;
    public $ds_col_url = null;
    public $ds_col_puri = null;

    public function __construct($pValues = null) {
        parent::__construct("meta_datasources",
                "id",
                $pValues);
        $this->_getMandatory(array("ds_title", "ds_col_pk", "ds_col_name", "ds_srs", "ds_coord_prec"));

        $this->_select = sprintf("SELECT DISTINCT SQL_CALC_FOUND_ROWS t.* FROM meta_datasources t INNER JOIN meta_usr_datasources_acl m ON (m.datasource_id = t.id AND m.usr_id=%s AND m.access >= %s)",
                cUsr(),
                cLevel());
    }

    /**
     * Get the field names from the respective datasources
     * @param int $pDatasourceID The ID of the desired data source
     * @return array|boolean Array of field names on success, false on error
     */
    public function getDatasourceFieldNames($pDatasourceID) {
        if (Db::tableExists("ds" . $pDatasourceID)) {
            $mRes = Db::query("SELECT * FROM ds" . $pDatasourceID . " LIMIT 1;");
            if ($mRes !== false && mysqli_num_rows($mRes) >= 1) {
                $mReturn = array_keys(mysqli_fetch_assoc($mRes));
                return $mReturn;
            }
        }
        return false;
    }

    /**
     * 
     * @param int $pDatasourceID
     * @param W2UIRetObj $pRetObj
     * @return boolean
     */
    public static function deleteDatasource($pDatasourceID, &$pRetObj) {
        $mMetaDatasource = new MetaDatasources();
        $mMetaDatasource->id = $pDatasourceID;
        $mMetaDatasource->delete($pRetObj);
        
        MetaAcl::DeleteDatasourceAclEntries($pDatasourceID);
        
        $mSql = "DELETE FROM meta_datasources WHERE id = $pDatasourceID;
            DROP TABLE IF EXISTS ds".$pDatasourceID.";
            DROP TABLE IF EXISTS ds".$pDatasourceID."_cat;
            DROP TABLE IF EXISTS ds".$pDatasourceID."_adm0;
            DROP TABLE IF EXISTS ds".$pDatasourceID."_adm1;
            DROP TABLE IF EXISTS ds".$pDatasourceID."_match;";
        $mRes = Db::query_multi($mSql);
        if ($mRes !== false) {
            $pRetObj->setSuccess("Successfully deleted data source tables");
            return true;
        } else {
            $pRetObj->setFailure("Error deleting data source tables");
            return false;
        }
    }

}
