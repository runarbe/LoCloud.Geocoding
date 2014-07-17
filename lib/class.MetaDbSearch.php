<?php
class MetaDbSearch extends MySQLTable {

    public $id;
    public $sch_title;
    public $sch_table;
    public $sch_display = "name";
    public $sch_lev1 = "admin1 code";
    public $sch_lev2 = "admin2 code";
    public $sch_lev3 = "admin3 code";
    public $sch_epsg = "4326";
    public $sch_lon = "longitude";
    public $sch_lat = "latitude";
    public $sch_like = "name;asciiname;alternatenames";
    public $sch_eq = "NULL";
    public $sch_webservice = "ws-search-geonames";

    public function __construct($pValues = null) {
        $this->_table = "meta_dbsearch";
        $this->_pk = "id";
        $this->_getMandatory(array("sch_title", "sch_table"));
        parent::__construct("meta_dbsearch", "id", $pValues);
    }
    
}
