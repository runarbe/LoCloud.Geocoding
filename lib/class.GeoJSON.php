<?php

class GeoJSON {

    private $GeoJSON;

    function __construct() {
        $this->GeoJSON = new stdClass();
        $this->GeoJSON->type = "FeatureCollection";
        $this->GeoJSON->features = array();
    }
    
    public function createFeature($pGeometry, $pProperties) {
        $mFeature = new feature($pGeometry, $pProperties);
        $this->GeoJSON->features[] = $mFeature;
    }

    public function getGeoJSON() {
        return json_encode($this->GeoJSON);
    }

}

class feature {

    public $type = "Feature";
    public $geometry;
    public $properties;
    
    public function __construct($pGeometry, $pProperties) {
        $this->geometry = $pGeometry;
        $this->properties = $pProperties->getProperties();
    }

}

class geometry {
    public $type;
    public $coordinates;

    function __construct($pType, $pCoordinates) {
        $this->type = $pType;
        $this->coordinates = $pCoordinates;
    }
}

class properties {
    public $propertyArray;
    public function __construct($pProperties) {
        $this->propertyArray = $pProperties;
    }
    
    public function getProperties() {
        return (object)$this->propertyArray;
    }
    
}

?>
