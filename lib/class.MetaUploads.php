<?php

class MetaUploads extends MySQLTable {

    public $id;
    public $fk_meta_usr_id;
    public $fname;

    public function __construct($pValues = null) {
        parent::__construct("meta_uploads", "id", $pValues);
        $this->setMandatory(array("id", "fk_meta_usr_id", "fname"));
    }

}

?>
