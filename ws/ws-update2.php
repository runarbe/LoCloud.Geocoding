<?php

require_once("../functions.php");

class WsUpdateItem extends GcWebService implements iWebService {

    protected function _execute() {

        $mCheck = array(
            "datasourceID" => new ParamOpt(true,
                    WsDataTypes::mInteger),
            "itemID" => new ParamOpt(true,
                    WsDataTypes::mInteger),
            "x" => new ParamOpt(true,
                    WsDataTypes::mDouble),
            "y" => new ParamOpt(true,
                    WsDataTypes::mDouble),
            "confidence" => new ParamOpt(true,
                    WsDataTypes::mDouble),
            "mapResolution" => new ParamOpt(true,
                    WsDataTypes::mDouble),
            "pURI" => new ParamOpt(false,
                    WsDataTypes::mString,
                    null),
            "name" => new ParamOpt(true,
                    WsDataTypes::mString),
            "fieldChanges" => new ParamOpt(false,
                    WsDataTypes::mString)
        );


        $mP = $this->_getParams($mCheck);

        if ($this->_isSuccess()) {

            if (!isEscaped($mP["name"])) {
                $mP["name"] = Db::esc($mP["name"]);
            }
            if (!isEscaped($mP["fieldChanges"])) {
                $mP["fieldChanges"] = Db::esc($mP["fieldChanges"]);
            }

            /*
             * Determine the name of the table to update based on the datasource id and the naming rules
             */
            $mTableName = "ds" . $mP["dsID"] . "_match";

            /*
             * Upsert record into database - user ID + item ID is unique key in the table
             */
            $mSql = sprintf('INSERT INTO %s (fk_ds_id, gc_name, gc_lon, gc_lat,gc_probability, gc_geom, gc_fieldchanges, gc_usr_id) VALUES (%s, \'%s\', null, %s, %s, %s, \'%s\', \'%s\', %s) ON DUPLICATE KEY UPDATE SET gc_name=\'%s\', gc_lon=%s, gc_lat=%s ,gc_probability=%s, gc_geom=\'%s\', gc_fieldchanges=\'%s\'',
                    $mTableName,
                    $mP["itemID"],
                    $mP["name"],
                    $mP["lon"],
                    $mP["lat"],
                    $mP["confidence"],
                    $mP["fieldChanges"],
                    cUsr(),
                    $mP["name"],
                    $mP["lon"],
                    $mP["lat"],
                    $mP["confidence"],
                    $mP["fieldChanges"]
            );
            Db::query($mSql);
            if (mysqli_affected_rows(Db::$conn) >= 1) {
                $this->_result->setSuccess();
            } else {
                $this->_result->setFailure(ErrorMsgs::dbErrorInsertCheckLog);
            }
        }
        
        $this->_result->echoJson();
    }

    public static function getInstance() {
        return new WsUpdateItem();
    }

}

WsUpdateItem::getInstance()->run(true);