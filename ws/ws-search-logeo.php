<?php

require_once("../functions.php");

/**
 * Description of ws-search-logeo
 *
 * @author runarbe
 */
class WsSearchLoGeo extends GcWebService implements iWebService {

    private $_apiKey;
    private $_q;

    protected function _execute() {

        $mCheck = array(
            "q" => new ParamOpt(true,
                    WsDataTypes::mString),
            "t" => new ParamOpt(true,
                    WsDataTypes::mString),
            "apiKey" => new ParamOpt(true,
                    WsDataTypes::mString,
                    "xxxxxxxx")
        );

        $mP = $this->_getParams($mCheck);

        if ($this->_isSuccess()) {

            $this->_apiKey = $mP["apiKey"];
            $this->_q = $mP["q"];

            switch ($mP["t"]) {
                case "Geonames":
                    $this->searchService("Geonames");
                    break;
                case "Google":
                    $this->searchService("Google");
                    break;
                case "National":
                    $this->searchService("National");
                    break;
                default:
                    $this->_notSupported();
                    break;
            }
        }

        $this->_result->echoJson();
    }

    public static function getInstance() {
        return new WsSearchLoGeo();
    }

    /**
     * Execute a search towards the LoCloud Geocoding API
     * @param String $pServiceKeyword One of "Google", "Geonames" or "National"
     */
    private function searchService($pServiceKeyword) {
        $mJson = json_decode(
                $this->_curlGetJSON(
                        sprintf('http://locloudgeo.eculturelab.eu/LoGeo_1_2/loGeo.aspx?'
                                . 'InputText=%s&MaxOutput=10'
                                . '&PreferableSource=%s&Key=%s',
                                $this->_q,
                                $pServiceKeyword,
                                $this->_apiKey
                        )
                )
        );

        $mID = 0;
        if (count($mJson) > 0) {
            foreach ($mJson as $mRecords) {
                foreach ($mRecords as $mRecord) {
                    $this->_result->addData(SearchMatch::get($mID,
                                    $mRecord->PlaceX,
                                    $mRecord->PlaceY,
                                    $mRecord->PlaceName));
                    $mID++;
                }
            }
        }
    }

}

WsSearchLoGeo::getInstance()->run(true);