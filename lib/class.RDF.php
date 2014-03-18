<?php

/**
 * Description of class
 *
 * @author runarbe
 */
class RDF {
    public $Doc;
    public $Root;
    
    public function __construct() {
        $this->Doc = new DOMDocument( "1.0", "UTF-8" );
        $this->Root = $this->newElement("rdf:RDF");
        $this->Doc->appendChild($this->Root);
        $this->Root->setAttribute("xmlns:gn", "http://www.geonames.org/ontology#");
        $this->Root->setAttribute("xmlns:rdf", "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
        $this->Root->setAttribute("xmlns:rdfs", "http://www.w3.org/2000/01/rdf-schema#");
        $this->Root->setAttribute("xmlns:wgs84_pos", "http://www.w3.org/2003/01/geo/wgs84_pos#");
        $this->Root->setAttribute("xmlns:owl", "http://www.w3.org/2002/07/owl#");
    }
        
    public function newElement($pTag, $pValue = null) {
        $mNewElement = $this->Doc->createElement($pTag, $pValue);
        return $mNewElement;
    }
    
    public function addElement($pElement, $pParentElement = null) {
        if ($pParentElement == null) {
            $this->Root->appendChild($pElement);
        } else {
            $pParentElement->appendChild($pElement);
        }
    }
    
    public function addDesc($pUri, $pLat, $pLon, $pUrl = null) {
        
        $mDesc = $this->newElement("rdf:Description");
        $mDesc->setAttribute("rdf:about", $pUri);
        
        $mLon = $this->newElement("geo:lon", $pLon);
        $mDesc->appendChild($mLon);

        $mLat = $this->newElement("geo:lat", $pLat);
        $mDesc->appendChild($mLat);
        
        //$this->Root->appendChild($mDesc);
        $this->addElement($mDesc);
    }
    
    public function getXml() {
        return $this->Doc->saveXML();
    }
    
}
?>
