<?php

require_once("../functions.php");

/**
 * Description of ws-search-logeo
 *
 * @author runarbe
 */
class WsSearchGeonamesAPI extends GcWebService implements iWebService {

    /**
     * API key
     * @var String 
     */
    protected $_apiKey;

    /**
     * Query term
     * @var String 
     */
    protected $_q;

    /**
     * Main function body
     */
    protected function execute() {

        $mCheck = array(
            "q" => new ParamOpt(true,
                    WsDataTypes::mString),
            "t" => new ParamOpt(true,
                    WsDataTypes::mString),
            "apiKey" => new ParamOpt(true,
                    WsDataTypes::mString,
                    "locloudgc")
        );

        $mP = $this->_getParams($mCheck);

        if ($this->isSuccess()) {

            $this->_apiKey = $mP["apiKey"];
            $this->_q = $mP["q"];

            switch ($mP["t"]) {
                case "searchJSON":
                    $this->searchJSON();
                    break;
                case "wikipediaSearchJSON":
                    $this->wikipediaSearchJSON();
                    break;
                default:
                    $this->operationNotSupported();
                    break;
            }
        }
        $this->_result->echoJson();
    }

    public static function getInstance() {
        return new WsSearchGeonamesAPI();
    }

    private function callGeonamesAPI($pUrl,
            $pData = null) {

        $mCurl = curl_init();

        if ($pData !== null) {
            $pUrl = sprintf("%s?%s",
                    $pUrl,
                    http_build_query($pData));
        }

        curl_setopt($mCurl,
                CURLOPT_URL,
                $pUrl);
        curl_setopt($mCurl,
                CURLOPT_RETURNTRANSFER,
                true);

        curl_setopt($mCurl,
                CURLOPT_HEADER,
                "Content-type: application/json");
        curl_setopt($mCurl,
                CURLOPT_HEADER,
                "Accept: application/json");
        curl_setopt($mCurl,
                CURLOPT_POSTFIELDS,
                "{}");

        return curl_exec($mCurl);
    }

    private function searchJSON() {
        $mJson = json_decode($this->callGeonamesAPI(
                        sprintf(
                                'http://api.geonames.org/searchJSON?q=%s&maxRows=10&lang=en&username=%s',
                                $this->_q,
                                $this->_apiKey)
                )
        );

        if (isset($mJson->geonames)) {
            foreach ($mJson->geonames as $mGeoname) {
                $this->_result->addData(SearchMatch::get($mGeoname->geonameId,
                                $mGeoname->lng,
                                $mGeoname->lat,
                                $mGeoname->toponymName,
                                $mGeoname->toponymName . ' (' . $mGeoname->fclName . ', ' . $mGeoname->adminName1 . ')'));
            }
        }
    }

    private function wikipediaSearchJSON() {

        $mJson = json_decode(
                $this->callGeonamesAPI(
                        sprintf(
                                'http://api.geonames.org/wikipediaSearchJSON?q=%s&maxRows=10&username=%s',
                                $this->_q,
                                $this->_apiKey)));

        if (isset($mJson->geonames)) {
            foreach ($mJson->geonames as $mArticle) {
                $this->_result->addData(SearchMatch::get($mArticle->geoNameId,
                                $mArticle->lng,
                                $mArticle->lat,
                                $mArticle->title,
                                $mArticle->title . ' (' . $mArticle->feature . ', ' . $mArticle->countryCode . ')'));
            }
        }
    }

}

WsSearchGeonamesAPI::getInstance()->run(true);
