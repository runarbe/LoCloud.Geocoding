<?php

require_once("../functions.php");

class WsAuth extends GcWebService implements iWebService {

    public function execute() {
        switch ($this->_operation) {
            case "auth":
            default:
                $this->authenticate();
                break;
        }
        $this->_result->echoJson();
    }

    private function authenticate() {
        $mCheck = array(
            "u" => new ParamOpt(true,
                    WsDataTypes::mString),
            "p" => new ParamOpt(true,
                    WsDataTypes::mString)
        );

        $p = $this->_getParams($mCheck);

        if ($this->isSuccess()) {
            if (false !== ($mUsr = MetaUsr::GetUsr($p["u"],
                            $p["p"]))) {
                session_start();
                $_SESSION ["usr_id"] = $mUsr->id;
                $_SESSION ["usr_level"] = $mUsr->level;
                $_SESSION ["usr_usr"] = $mUsr->usr;
                
                $this->_result->setSuccess(ErrorMsgs::authSuccess);
            } else {
                $this->_result->setFailure(ErrorMsgs::noSuchUserOrWrongAuth);
            }
        }
    }

    public static function getInstance() {
        return new WsAuth();
    }

}

WsAuth::getInstance()->run();