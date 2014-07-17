<?php

require_once("../functions.php");
dieIfSessionExpired();
$v = 1;

class jSrbGeoJson {

    public $type = "FeatureCollection";
    public $features = array();

    public function addFeature($feature) {
        $this->features[] = $feature;
    }

}

class jSrbGeoJsonFeature {

    public $type = "Feature";
    public $properties = array();
    public $geometry;

    function __construct() {
        $this->geometry = new jSrbGeoJsonGeometryPoint;
    }

}

class jSrbGeoJsonGeometryPoint {

    public $type = "Point";
    public $coordinates = array();

    public function setXY($x, $y) {
        $this->coordinates[] = $x;
        $this->coordinates[] = $y;
    }

}

if (!isset($_GET["dsID"])) {
    $v = -1;
}

if (!isset($_GET["bbox"])) {
    $v = -1;
} else {
    $bbox = explode(",", $_GET["bbox"]);
}

$json = new jSrbGeoJson();
if ($v == 1) {
    $mDb = db();

    $t1 = "ds" . $_GET["dsID"];
    $t2 = "ds" . $_GET["dsID"] . "_match";

    $mSql = "SELECT gc_name, gc_lon, gc_lat FROM " . $t1 . " t1 LEFT JOIN " . $t2 . " t2 ON t2.fk_ds_id=t1.id  WHERE (gc_lon BETWEEN ".$bbox[0]." AND ".$bbox[2].") AND (gc_lat BETWEEN ".$bbox[1]." AND ".$bbox[3].") AND gc_probability <= 2 LIMIT 100";
    $result = $mDb->query($mSql);
    if ($result) {
        while ($obj = $result->fetch_object()) {
            $feature = new jSrbGeoJsonFeature();
            $feature->geometry->setXY($obj->gc_lon, $obj->gc_lat);
            $feature->properties = $obj;
            $json->addFeature($feature);
        }
    } else {
        echo mysqli_error($mDb);
    }
    dbc($mDb);
}
echo json_encode($json);