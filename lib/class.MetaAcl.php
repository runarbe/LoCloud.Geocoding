<?php

/**
 * Description of class
 *
 * @author runarbe
 */
class MetaAcl extends MySQLTable {

    /**
     * Primary key
     * @var Integer 
     */
    public $id = null;

    /**
     * Record id (copy of id) used by W2UI
     * @var Integer
     */
    public $recid = null;

    /**
     * Source type
     * @var String  
     */
    public $src_type = null;

    /**
     * Source id
     * @var Integer 
     */
    public $src_id = null;

    /**
     * Target type
     * @var String  
     */
    public $tgt_type = null;

    /**
     * Targer id
     * @var Integer 
     */
    public $tgt_id = null;

    /**
     * The access level of the user
     * @var AccessLevels 
     */
    public $access = AccessLevels::Editor;

    /**
     * Default constructor
     * @param array $pValues
     */
    public function __construct($pValues = null) {
        $this->_getMandatory(array("src_type", "src_id", "tgt_type", "tgt_id", "access"));
        parent::__construct("meta_acl",
                "id",
                $pValues);
        $this->_select = "SELECT DISTINCT SQL_CALC_FOUND_ROWS m.label, u.usr, t.* FROM meta_acl t INNER JOIN meta_usr u ON (u.id = t.src_id AND t.src_type='meta_usr') LEFT JOIN meta_access m ON (t.access = m.id)";
    }

    /**
     * Add a user to the access control list of a data source
     * @param int $pUsrID
     * @param int $pDatasourceID
     * @param AccessLevels $pAccess
     * @param W2UIRetObj $pRetObj
     * @return void
     */
    public static function AddUsrToDatasourceAcl($pUsrID,
            $pDatasourceID,
            $pAccess,
            &$pRetObj = null) {
        $mAcl = new MetaAcl();
        $mAcl->src_type = "meta_usr";
        $mAcl->src_id = $pUsrID;
        $mAcl->tgt_type = "meta_datasources";
        $mAcl->tgt_id = $pDatasourceID;
        $mAcl->access = $pAccess;
        return $mAcl->insert($pRetObj);
    }

    /**
     * Add a user to the access control list of a user
     * @param int $pUsrID
     * @param int $pUsrID2
     * @param AccessLevels $pAccess
     * @param W2UIRetObj $pRetObj
     * @return void
     */
    public static function AddUsrToUsrAcl($pUsrID,
            $pUsrID2,
            $pAccess,
            &$pRetObj = null) {
        $mAcl = new MetaAcl();
        $mAcl->src_type = "meta_usr";
        $mAcl->src_id = $pUsrID;
        $mAcl->tgt_type = "meta_usr";
        $mAcl->tgt_id = $pUsrID2;
        $mAcl->access = $pAccess;
        return $mAcl->insert($pRetObj);
    }

    /**
     * Checks whether a user has rights to delete a datasource
     * @param type $pUsrID
     * @param type $pDatasourceID
     * @return bool
     */
    public static function UsrCanDeleteDatasource($pUsrID,
            $pDatasourceID) {
        $mTest = Db::executeScalar(sprintf("SELECT EXISTS (SELECT * FROM meta_acl WHERE src_type='meta_usr' AND tgt_type='meta_datasources' AND src_id=%s AND tgt_id=%s AND access=%s)",
                                $pUsrID,
                                $pDatasourceID,
                                AccessLevels::Owner));
        return $mTest == 1 ? true : false;
    }

    /**
     * Checks whether a user has rights to edit a datasource
     * @param type $pUsrID
     * @param type $pDatasourceID
     * @return bool
     */
    public static function UsrCanEditDatasource($pUsrID,
            $pDatasourceID) {
        $mTest = Db::executeScalar(sprintf("SELECT EXISTS (SELECT * FROM meta_acl WHERE src_type='meta_usr' AND tgt_type='meta_datasources' AND src_id=%s AND tgt_id=%s AND access <= %s)",
                                $pUsrID,
                                $pDatasourceID,
                                AccessLevels::Owner));
        return $mTest == 1 ? true : false;
    }

    /**
     * Checks whether a user has rights to edit a datasource
     * @param type $pUsrID
     * @param type $pDatasourceID
     * @return bool
     */
    public static function UsrCanViewDatasource($pUsrID,
            $pDatasourceID) {
        $mTest = Db::executeScalar(sprintf("SELECT EXISTS (SELECT * FROM meta_acl WHERE src_type='meta_usr' AND tgt_type='meta_datasources' AND src_id=%s AND tgt_id=%s AND access <= %s)",
                                $pUsrID,
                                $pDatasourceID,
                                AccessLevels::Contributor));
        return $mTest == 1 ? true : false;
    }

    /**
     * Checks whether a user has rights to manage the ACL of a datasource
     * @param type $pUsrID
     * @param type $pDatasourceID
     * @return bool
     */
    public static function UsrCanManageDatasourcesAcl($pUsrID,
            $pDatasourceID) {
        $mSql = sprintf("SELECT EXISTS(SELECT * FROM meta_acl WHERE src_type='meta_usr' AND tgt_type='meta_datasources' AND src_id=%s AND tgt_id=%s AND access<=%s)",
                $pUsrID,
                $pDatasourceID,
                AccessLevels::Editor);
        $mTest = Db::executeScalar($mSql);
        return $mTest == 1 ? true : false;
    }

    /**
     * Checks whether a user has rights to delete a datasource
     * @param type $pUsrID
     * @param type $pUsrID2
     * @return bool
     */
    public static function UsrCanDeleteUsr($pUsrID,
            $pUsrID2) {
        $mTest = Db::executeScalar(sprintf("SELECT EXISTS(SELECT * FROM meta_acl WHERE src_type='meta_usr' AND tgt_type='meta_usr' AND src_id=%s AND tgt_id=%s AND access=%s)",
                                $pUsrID,
                                $pUsrID2,
                                AccessLevels::Owner));
        return $mTest == 1 ? true : false;
    }

    /**
     * Checks whether a user has rights to edit a datasource
     * @param type $pUsrID
     * @param type $pUsrID2
     * @return bool
     */
    public static function UsrCanEditUsr($pUsrID,
            $pUsrID2) {
        $mTest = Db::executeScalar(sprintf("SELECT EXISTS(SELECT * FROM meta_acl WHERE src_type='meta_usr' AND tgt_type='meta_usr' AND src_id=%s AND tgt_id=%s AND access <= %s)",
                                $pUsrID,
                                $pUsrID2,
                                AccessLevels::Editor));
        return $mTest == 1 ? true : false;
    }

    /**
     * Delete ACL entries for a user
     * @param type $pUsrID
     * @return boolean
     */
    public static function DeleteUsrAclEntries($pUsrID) {
        Db::executeScalar(sprintf("DELETE FROM meta_acl WHERE (src_type='meta_user' AND src_id=%s) OR (tgt_type='meta_usr' AND tgt_id=%s)",
                        $pUsrID,
                        $pUsrID));
        return true;
    }

    /**
     * Delete ACL entries for an object
     * @param type $pDatasourceID
     * @return boolean
     */
    public static function DeleteDatasourceAclEntries($pDatasourceID) {
        Db::executeScalar(sprintf("DELETE FROM meta_acl WHERE (src_type='meta_datasources' AND src_id=%s) OR (tgt_type='meta_datasources' AND tgt_id=%s)",
                        $pDatasourceID,
                        $pDatasourceID));
        return true;
    }

}
