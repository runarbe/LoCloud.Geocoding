<?php
/**
 * This class creates a new KML 2.2 point file
 * @author (Stein) Runar Bergheim, NORPLAN A/S Oman Branch
 */
class KML {

    private $version = "2.2";
    private $kml;
    private $kmlRoot;
    private $kmlDoc;

    function __construct() {
        $this->kml = new DOMDocument("1.0", "UTF-8");
        $this->kmlRoot = $this->kml->createElement("kml");
        $this->kmlRoot->setAttribute("xmlns", "http://www.opengis.net/kml/" . $this->version);
        $this->kml->appendChild($this->kmlRoot);
        $this->kmlDoc = $this->kml->createElement("Document");
        $this->kmlRoot->appendChild($this->kmlDoc);
        $this->addStyle("default");
    }

    function addStyle($pStyleID, $pIconImage = null, $pHtmlText = "<strong>$[name]</strong><br/>$[description]") {
        $mStyle = $this->kml->createElement("Style");
        $mStyle->setAttribute("id", $pStyleID);
        $this->kmlDoc->appendChild($mStyle);
        $mBalloonStyle = $this->addBalloonStyle($pHtmlText);
        $mStyle->appendChild($mBalloonStyle);
        if ($pIconImage != null) {
            $mIconStyle = $this->addIconStyle($pIconImage);
            $mStyle->appendChild($mIconStyle);
        }
    }

    function addBalloonStyle($pHtmlText) {
        $mBalloonStyle = $this->kml->createElement("BalloonStyle");
        $mBalloonStyle->appendChild($this->kml->createElement("bgColor", "#ffffff"));
        $mText = $this->kml->createElement("text");
        $mCData = $this->kml->createCDATASection($pHtmlText);
        $mText->appendChild($mCData);
        $mBalloonStyle->appendChild($mText);
        return $mBalloonStyle;
    }

    function addIconStyle($pIconImage) {
        if ($pIconImage == null) {
            return "";
        } else {
            $mIconStyle = $this->kml->createElement("IconStyle");
            $mIconScale = $this->kml->createElement("scale", "1.1");
            $mIconStyle->appendChild($mIconScale);
            $mIcon = $this->kml->createElement("Icon");
            $mIconHref = $this->kml->createElement("href", $pIconImage);
            $mIcon->appendChild($mIconHref);
            $mIconStyle->appendChild($mIcon);            
            return $mIconStyle;
        }
    }

    function addPlacemark($mName, $mlong, $mlat, $mDesc = "", $mStyleUrl = "default") {
        $mPlacemark = $this->kmlDoc->appendChild($this->kml->createElement("Placemark"));
        $mNameTextNode = $this->kml->createTextNode($mName);
        $mNameElement = $this->kml->createElement("name");
        $mNameElement->appendChild($mNameTextNode);
        $mPlacemark->appendChild($mNameElement);
        
        $mPlacemark->appendChild($this->kml->createElement("description", $mDesc));
        $mPlacemark->appendChild($this->kml->createElement("styleUrl", "#" . $mStyleUrl));
        $mPoint = $mPlacemark->appendChild($this->kml->createElement("Point"));
        $mPoint->appendChild($this->kml->createElement("coordinates", $mlong . "," . $mlat . ",0"));
    }

    public function getKml() {
        return $this->kml->saveXML();
    }

}

?>