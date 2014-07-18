<?php

require_once("../functions.php");

/**
 * Description of ws-search-logeo
 *
 * @author runarbe
 */
class WsSearchGeonamesAPI extends GcWebService implements iWebService {

    protected function execute() {
        var_export($this->callGeonamesAPI('http://api.geonames.org/search?q=londoz&maxRows=10&fuzzy=0.8&username=demo'));
        //$this->_result->echoJson();
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
        curl_setopt($mCurl,CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($mCurl, CURLOPT_HEADER, "Content-type: application/json");
        curl_setopt($mCurl, CURLOPT_HEADER, "Accept: application/json");
        curl_setopt($mCurl, CURLOPT_POSTFIELDS, "{}");

        return curl_exec($mCurl);
    }

}

WsSearchGeonamesAPI::getInstance()->run(true);
