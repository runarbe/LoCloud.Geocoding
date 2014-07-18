<?php

require_once("../functions.php");

class WsSearchGeonames extends GcWebService implements iWebService {

    protected function execute() {

        $mCheck = array(
            "t" => new ParamOpt(true,
                    WsDataTypes::mString),
            "q" => new ParamOpt(true,
                    WsDataTypes::mString,
                    "%"),
            "bbox" => new ParamOpt(false,
                    WsDataTypes::mString,
                    null)
        );

        $mP = $this->_getParams($mCheck);

        if ($this->isSuccess()) {

            $mWhere = array();

            // Test if bbox is present in request (not mandatory)
            if (isset($mP["bbox"]) && $mP['bbox'] !== '' && $mP['bbox'] !== null) {
                $bbox = explode(",",
                        $_GET["bbox"]);
                $mWhere[] = "(longitude BETWEEN " . $bbox[0] . " AND " . $bbox[2] . ") AND (latitude BETWEEN " . $bbox[1] . " AND " . $bbox[3] . ")";
            }

            $mP["q"] = strtolower($mP["q"]);

            $mWhere[] = "(name LIKE '" . $mP["q"] . "%' OR asciiname LIKE '" . $mP["q"] . "%' OR alternatenames LIKE '%" . $mP["q"] . "%')";

            $mSql = "SELECT * FROM " . $mP["t"] . " WHERE" . implode(" AND ",
                            $mWhere) . "ORDER BY name ASC LIMIT 10";

            if (false !== ($mRes = Db::query($mSql))) {
                $i = 0;
                while (null != ($mRow = $mRes->fetch_assoc())) {
                    
                    $this->_result->addData(SearchMatch::get($mRow['geonameid'],
                                    $mRow['longitude'],
                                    $mRow['latitude'],
                                    $mRow['name']));
                    $i++;
                }

                if ($i === 0) {
                    $this->_result->setSuccess(ErrorMsgs::noResults);
                } else {
                    $this->_result->total = count($this->_result->records);
                    $this->_result->setSuccess();
                }
            } else {
                $this->_result->setFailure(ErrorMsgs::database);
            }
        }

        $this->_result->echoJson();
    }

    public static function getInstance() {
        return new WsSearchGeonames();
    }

}

WsSearchGeonames::getInstance()->run(true);
