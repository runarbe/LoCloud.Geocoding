<?php

require_once("../functions.php");

class WsGetAdm1Areas extends GcWebService implements iWebService {

    protected function _execute() {

        $mCheck = array(
            't' => new ParamOpt(true,
                    WsDataTypes::mString),
            'a0' => new ParamOpt(true,
                    WsDataTypes::mString)
        );

        $mP = $this->_getParams($mCheck);
        if ($this->_isSuccess()) {

            if (!isEscaped($mP['a0'])) {
                $mP['a0'] = Db::esc($mP['a0']);
            }

            $mSql = sprintf('SELECT adm1, minx, miny, maxx, maxy FROM %s WHERE adm0=\'%s\' ORDER BY adm1 ASC',
                    $mP["t"],
                    $mP["a0"]);
            if (false !== ($result = Db::query($mSql))) {
                $mResCount = 0;
                while ($mArea1 = $result->fetch_object()) {
                    $this->_result->addData($mArea1);
                    $mResCount++;
                }

                if ($mResCount === 0) {
                    $this->_result->setFailure(ErrorMsgs::noResults);
                }
            } else {
                $this->_result->setFailure(ErrorMsgs::dbErrorSelectCheckLog);
            }
        }

        $this->_result->echoJson();
    }

    public static function getInstance() {
        return new WsGetAdm1Areas();
    }

}

WsGetAdm1Areas::getInstance()->run(true);
