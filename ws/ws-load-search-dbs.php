<?php

require_once("../functions.php");
dieIfSessionExpired();

class WsLoadSearchDBs extends GcWebService implements iWebService {

    protected function _execute() {
        $mSql = "SELECT * FROM meta_dbsearch ORDER BY sch_title ASC";
        if (false !== ($result = Db::query($mSql))) {
            while ($mSearchDB = $result->fetch_object()) {
                $this->_result->addData($mSearchDB);
            }
        } else {
            $this->_result->setFailure(ErrorMsgs::dbErrorSelectCheckLog,
                    get_class($this));
        }
        $this->_result->echoJson();
    }

    public static function getInstance() {
        return new WsLoadSearchDBs();
    }

}

WsLoadSearchDBs::getInstance()->run(true);
