<?php

require_once("../functions.php");

/**
 * Description of ws-search-logeo
 *
 * @author runarbe
 */
class WsSearchLoGeo extends GcWebService implements iWebService {

    protected function execute() {
        var_export($this->callLogeoApi('http://locloudgeo.eculturelab.eu/LoGeo_1_0/loGeo.aspx?Place=Brussels&ContextPlace=Belgium&MaxOutput=10&PreferableSource=Geonames&Key=xxxxxxxx'));
        //$this->_result->echoJson();
    }

    public static function getInstance() {
        return new WsSearchLoGeo();
    }

    private function callLogeoApi($pUrl,
            $pData = false) {
        
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
                1);

        return curl_exec($mCurl);
    }

}

WsSearchLoGeo::getInstance()->run(true);
