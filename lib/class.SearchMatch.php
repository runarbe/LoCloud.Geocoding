<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchMatch
 *
 * @author runarbe
 */
class SearchMatch {
    
    /**
     *
     * @var type 
     */
    public $displayName;
    
    public $name;
    
    public $lng;
    
    public $lat;
    
    public $wkt;
    
    public $confidence;
    
    public function __construct($pName = null, $pDisplayName = null, $pLng = null, $pLat = null, $pWkt = null, $pConfidence = 1) {
        if ($pName !== null) {
            $this->name = $pName;
        }
        
        if ($pDisplayName !== null) {
            $this->displayName = $pDisplayName;
        } else if ($pDisplayName === null && $pName !== null) {
            $this->name = $pName;
        }
        
        if (is_numeric($pLng) && is_numeric($pLat)) {
            $this->lng = $pLng;
            $this->lat = $pLat;
        }
        
        if ($pWkt !== null) {
            $this->wkt = $pWkt;
        }
        
        if (is_numeric($pConfidence)) {
            $this->confidence = $pConfidence;
        }
    }
    
}
