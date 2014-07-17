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
        $this->Doc = new DOMDocument("1.0",
                "UTF-8");
        $this->Root = $this->newElement("rdf:RDF");
        $this->Doc->appendChild($this->Root);
        $this->Root->setAttribute("xmlns:gn",
                "http://www.geonames.org/ontology#");
        $this->Root->setAttribute("xmlns:rdf",
                "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
        $this->Root->setAttribute("xmlns:rdfs",
                "http://www.w3.org/2000/01/rdf-schema#");
        $this->Root->setAttribute("xmlns:wgs84_pos",
                "http://www.w3.org/2003/01/geo/wgs84_pos#");
        $this->Root->setAttribute("xmlns:owl",
                "http://www.w3.org/2002/07/owl#");
        $this->Root->setAttribute("xmlns:skos",
                "http://www.w3.org/2004/02/skos/core#");
    }

    public function newElement($pTag,
            $pValue = null) {
        $mNewElement = $this->Doc->createElement($pTag,
                $pValue);
        return $mNewElement;
    }

    public function addElement($pElement,
            $pParentElement = null) {
        if ($pParentElement == null) {
            $this->Root->appendChild($pElement);
        } else {
            $pParentElement->appendChild($pElement);
        }
    }

    /**
     * Add a new rdf:about record to the RDF file
     * @param String $pGcPuri
     * @param Float $pGcLat
     * @param Float $pGcLon
     * @param Float $pGcProbability
     * @param String $pGcOrigName
     * @param String $pGcName
     * @param String $pGcDbsearchPuri
     */
    public function addDesc($pGcPuri,
            $pGcLat,
            $pGcLon,
            $pGcProbability = null,
            $pGcOrigName = null,
            $pGcName = null,
            $pGcDbsearchPuri = null) {
        
        $mDesc = $this->newElement("rdf:Description");
        $mDesc->setAttribute("rdf:about",
                $pGcPuri);

        $mDesc->appendChild($this->newElement("geo:lon",
                        $pGcLon));
        $mDesc->appendChild($this->newElement("geo:lat",
                        $pGcLat));

        if ($pGcProbability !== null) {
            $mDesc->appendChild($this->newElement("probability",
                            $pGcProbability));
        }

        if ($pGcOrigName !== null) {
            $mDesc->appendChild($this->newElement("skos:prefLabel",
                            $pGcOrigName));
        }

        if ($pGcOrigName !== null) {
            $mDesc->appendChild($this->newElement("skos:prefLabel",
                            $pGcOrigName));
        }

        if ($pGcName != $pGcOrigName && $pGcName != null && $pGcOrigName != null) {
            $mDesc->appendChild($this->newElement("skos:altLabel",
                            $pGcName));
        }

        if ($pGcDbsearchPuri !== null) {
            $mDesc->appendChild($this->newElement("owl:sameAs",
                            $pGcDbsearchPuri));
        }

        $this->addElement($mDesc);
    }

    public function getXml() {
        return $this->Doc->saveXML();
    }

}
