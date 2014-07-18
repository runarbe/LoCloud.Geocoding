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

    protected function execute() {

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
                default:
                    $this->_operationNotSupported();
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
     * @param String $pServiceKeyword One of "Google", "Geonames" or "LoOnto"
     */
    private function searchService($pServiceKeyword) {
        $mJson = json_decode(
                $this->curlHttpGetJSON(
                        sprintf('http://locloudgeo.eculturelab.eu/LoGeo_1_0/loGeo.aspx?'
                                . 'Place=%s&ContextPlace=Norway&MaxOutput=10'
                                . '&PreferableSource=%s&Key=%s',
                                $this->_q,
                                $pServiceKeyword,
                                $this->_apiKey
                        )
                )
        );

        $mID = 0;
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

WsSearchLoGeo::getInstance()->run(true);
