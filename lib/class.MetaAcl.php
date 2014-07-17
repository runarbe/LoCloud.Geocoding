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
     * @var WsUserLevel 
     */
    public $level = WsUserLevel::GuestUser;
    
    public function __construct($pValues = null) {
        $this->setMandatory(array("usr", "pwd", "level"));
        parent::__construct("meta_usr", "id", $pValues);
    }
}
?>
