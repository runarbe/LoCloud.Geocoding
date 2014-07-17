<?php

/**
 * Description of class
 *
 * @author runarbe
 */
class MetaUsr extends MySQLTable {

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
     * User name
     * @var String  
     */
    public $usr = null;

    /**
     * Password
     * @var String 
     */
    public $pwd = null;

    /**
     * The access level of the user
     * @var UserLevels 
     */
    public $level = UserLevels::GuestUser;

    public function __construct($pValues = null) {
        $this->_getMandatory(array("usr", "pwd", "level"));
        parent::__construct("meta_usr",
                "id",
                $pValues);
        $this->_select = "SELECT DISTINCT SQL_CALC_FOUND_ROWS m.label, t.* FROM meta_usr t LEFT JOIN meta_usr_level m ON (m.id = t.level)";
        
    }

    /**
     * Get all user records
     * @param int $pLimit
     * @param int $pOffset
     * @param W2UIRetObj $pW2UIRetObj
     * @return boolean
     */
    public function GetAll($pLimit = null,
            $pOffset = null,
            $pW2UIRetObj = null) {
        $mReturn = false;
        if (false !== ($mRes = $this->selectMap(null,
                "t.usr",
                $pLimit,
                $pOffset,
                $pW2UIRetObj,
                null,
                null,
                "id"))) {
        }
        return $mReturn;
    }
    
    /**
     * Return a user if exists
     * @param string $pUsr Username
     * @param string $pPwd Password
     * @return Object|bool A user object on success, false if user doesn't exist or error
     */
    public static function GetUsr($pUsr, $pPwd) {
        $mSql = sprintf("SELECT * FROM meta_usr WHERE usr='%s' AND pwd='%s'",
                $pUsr,
                $pPwd);
        $mRes = Db::query($mSql);
        if ($mRes !== false && mysqli_num_rows($mRes) >= 1) {
            return mysqli_fetch_object($mRes);
        } else {
            return false;
        }
    }
    
}
