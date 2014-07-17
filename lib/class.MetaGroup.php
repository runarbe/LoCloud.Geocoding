<?php

/**
 * Description of MetaGroup
 *
 * @author runarbe
 */
class MetaGroup extends MySQLTable {

    /**
     * Primary key
     * @var int
     */
    public $id;

    /**
     * Record id (copy of id) used by W2UI
     * @var int 
     */
    public $recid;

    /**
     * Name of group
     * @var string 
     */
    public $title;

    /**
     * Description of group
     * @var string 
     */
    public $description;

    function __construct($pValues = null) {
        $this->_getMandatory(array("title", "description"));
        parent::__construct("meta_group",
                "id",
                $pValues);
    }

    /**
     * Get a specific group
     * @param int $pMetaGroupId
     * @return MetaGroup|boolean
     */
    public static function Get($pMetaGroupId) {
        $mReturn = false;
        if (false != ($mRes = Db::query("SELECT * FROM meta_group WHERE id=$pMetaGroupId"))) {
            if (mysqli_num_rows($mRes) == 1) {
                $mObj = $mRes->fetch_object();
                $mReturn = object_to_class($mObj,
                        "MetaGroup",
                        "id");
            }
        }
        return $mReturn;
    }

    /**
     * Get all groups
     * @return MetaGroup[]|boolean
     */
    public static function GetAll() {
        $mReturn = false;
        if (false != ($mRes = Db::query("SELECT * FROM meta_group"))) {
            $mReturn = array();
            while ($mObj = $mRes->fetch_object()) {
                $mReturn[] = object_to_class($mObj,
                        "MetaGroup",
                        "id");
            }
        }
        return $mReturn;
    }

}
