<?php

/*
 * Web service to load adm0 areas
 * @author: Runar Bergheim
 * @date: 02.07.2013
 */
require_once("../functions.php");

class WsGetAdm0Areas extends GcWebService implements iWebService {

    protected function _execute() {

        $mCheck = array(
            "t" => new ParamOpt(true,
                    WsDataTypes::mString)
        );

        $mP = $this->_getParams($mCheck);

        if ($this->_isSuccess()) {

            $mSql = sprintf("SELECT adm0, minx, miny, maxx, maxy FROM %s ORDER BY adm0 ASC",
                    $mP["t"]);

            if (false !== ($mRes = Db::query($mSql))) {
                $mNumResults = 0;

                while (null !== ($mObj = $mRes->fetch_object())) {
                    $this->_result->addData($mObj);
                    $mNumResults++;
                }

                if ($mNumResults == 0) {
                    $this->_result->setFailure(ErrorMsgs::noResults);
                }
            } else {
                $this->_result->setFailure(ErrorMsgs::dbErroSelectCheckLog);
            }
        }
        $this->_result->echoJson();
    }

    public static function getInstance() {
        return new WsGetAdm0Areas();
    }

}

WsGetAdm0Areas::getInstance()->run(true);
