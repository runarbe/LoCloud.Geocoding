<?php

/**
 * Description of SearchMatch
 *
 * @author runarbe
 */
class SearchMatch {

    /**
     * Unique identifier of the name within the source
     * @var String 
     */
    public $id;

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
     * Persistent URI of the name
     * @var String 
     */
    public $pUri;

    /**
     * Default constructor
     */
    public function __construct() {
        
    }

    /**
     * Get a new, populated instance of a SearchMatch class
     * @param String $pID Identifier of name
     * @param String $pName Name
     * @param String $pDisplayName Display name
     * @param Double $pLng Longitude
     * @param Double $pLat Latitude
     * @param String $pWkt Well-known-text geometry
     * @param Double $pConfidence Confidence level of match
     */
    public static function get($pID,
            $pLng,
            $pLat,
            $pName,
            $pDisplayName = null,
            $pUri = null,
            $pWkt = null,
            $pConfidence = 1) {

        $mSearchMatch = new SearchMatch();

        $mSearchMatch->id = $pID;

        if (is_numeric($pLng) && is_numeric($pLat)) {
            $mSearchMatch->lng = $pLng;
            $mSearchMatch->lat = $pLat;
        }

        $mSearchMatch->name = $pName;

        if ($pDisplayName === null) {
            $mSearchMatch->displayName = $pName;
        } else {
            $mSearchMatch->displayName = $pDisplayName;
        }

        if ($pWkt !== null) {
            $mSearchMatch->wkt = $pWkt;
        }

        if ($pUri !== null) {
            $mSearchMatch->uri = $pUri;
        }

        if (is_numeric($pConfidence)) {
            $mSearchMatch->confidence = $pConfidence;
        }

        return $mSearchMatch;
    }

    /**
     * Check if the name has a WKT set
     * @return bool True if has WKT, false if not
     */
    public function hasWkt() {
        return $this->wkt !== null ? true : false;
    }

    /**
     * Check if the name has a persistent URI set
     * @return bool True if has persistent URI, false if not
     */
    public function hasUri() {
        return $this->uri !== null ? true : false;
    }

}
