<?php

/**
 * A utility class to aid resolution of names in the export
 *
 * @author runarbe
 */
class GeocodingMatch {
 
    /**
     * Longitude or X-coordinate
     * @var Float 
     */
    public $gc_lon;
    
    /**
     * Latitude or Y-coordinate
     * @var Float 
     */
    public $gc_lat;

    /**
     * Original name of geocoded object
     * @var String
     */
    public $gc_orig_name;
    
    /**
     * Alternate name of geocoded object
     * @var String 
     */
    public $gc_name;
    
    /**
     * Probability of the match
     * @var Integer
     */
    public $gc_probability;
    
    /**
     * The persistent URI of a matched object from a database
     * @var String 
     */
    public $gc_dbsearch_puri;

    /**
     * The persistent URI of the object
     * @var String 
     */
    public $gc_puri;
    
    /**
     * The category of the object
     * @var String
     */
    public $gc_cat;

}