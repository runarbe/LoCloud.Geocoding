<?php

require_once('../functions.php');

class WsSendPasswordReminder extends GcWebService implements iWebService {

    protected function _execute() {
        $this->_result->setFailure("Not implemented");
        $this->_result->echoJson();
    }

    public static function getInstance() {
        return new WsSendPasswordReminder();
    }

}

WsSendPasswordReminder::getInstance()->run(true);
