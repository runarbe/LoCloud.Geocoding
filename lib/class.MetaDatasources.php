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
    
    public function __construct($pValues = null) {
        parent::__construct("meta_datasources", "id", $pValues);
        $this->setMandatory(array("ds_title", "ds_col_pk", "ds_col_name", "ds_srs", "ds_coord_prec"));
    }
    
}
?>
