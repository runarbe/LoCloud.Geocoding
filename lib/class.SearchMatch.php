<?php

/**
 * Description of SearchMatch
 *
 * @author runarbe
 */
class SearchMatch {
    
    /**
     * The name to be displayed in the result list
     * @var String 
     */
    public $displayName;
    
    /**
     * The actual name (may be shorter, longer)
     * @var String
     */
    public $name;
    
    /**
     * The longitude of the name in WGS84 geographical coordinates
     * @var type 
     */
    public $lng;

    /**
     * The latitude of the name in WGS84 geographical coordinates
     * @var type 
     */
    public $lat;
    
    /**
     * Optionally a WKT geometry string that can contain a more complex geometry
     * @var String (WKT)
     */
    public $wkt;
    
    /**
     * A floating point number between 0 and 100 that expresses the confidence of
     * the search result
     * @var Float 
     */
    public $confidence;
    
    /**
     * Returns a new search result
     * @param String $pName Name
     * @param String $pDisplayName Display name
     * @param Double $pLng Longitude
     * @param Double $pLat Latitude
     * @param String $pWkt Well-known-text geometry
     * @param Double $pConfidence Confidence level of match
     */
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
