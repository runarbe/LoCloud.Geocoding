<?php

require_once("../functions.php");

class WsDatasourcesAcl extends GcWebService implements iWebService {

    public function execute() {

        switch ($this->_operation) {
            case "get-records":
                $this->getRecords();
                break;
            case "delete-records":
                $this->deleteRecords();
                break;
            case "add-record":
                $this->addRecord();
                break;
            default:
                $this->_operationNotSupported();
                break;
        }
        $this->_result->echoJson();
        return;
    }

    public static function getInstance() {
        return new WsDatasourcesAcl();
    }

    public function getRecords() {
        $mMetaAcl = new MetaAcl();

        $mCheck = array(
            "datasourceid" => new ParamOpt(true,
                    WsDataTypes::mInteger),
            "offset" => new ParamOpt(false,
                    WsDataTypes::mString),
            "limit" => new ParamOpt(false,
                    WsDataTypes::mString)
        );

        $mP = $this->_getParams($mCheck);

        if ($this->_isSuccess()) {

            if (null !== ($mAclArray = $mMetaAcl->selectMap(
                    sprintf("tgt_type='meta_datasources' AND tgt_id=%s",
                            $mP["datasourceid"]),
                    "t.access, u.usr",
                    $mP["limit"],
                    $mP["offset"],
                    $this->_result,
                    null,
                    null,
                    "id"))) {
                
            } else {
                $this->_result->setFailure(ErrorMsgs::noResults);
            }
        }
    }

    public function deleteRecords() {
        $mMetaAcl = new MetaAcl();

        $mCheck = array(
            "datasourceid" => new ParamOpt(true,
                    WsDataTypes::mInteger,
                    -1),
            "selected" => new ParamOpt(true,
                    WsDataTypes::mArray)
        );
        $mP = $this->_getParams($mCheck);

        if ($this->_isSuccess()) {
            foreach ($mP["selected"] as $mAclID) {
                if (MetaAcl::UsrCanManageDatasourcesAcl(cUsr(),
                                $mP["datasourceid"])) {
                    $mMetaAcl->id = $mAclID;
                    $mMetaAcl->delete($this->_result);
                } else {
                    $this->_result->setFailure(ErrorMsgs::insufficientRights("delete",
                                    "datasource access"));
                }
            }
        }
    }

    public function addRecord() {
        $mMetaAcl = new MetaAcl();
        $mCheck = array(
            "src_type" => new ParamOpt(true,
                    WsDataTypes::mString),
            "src_id" => new ParamOpt(true,
                    WsDataTypes::mInteger),
            "tgt_type" => new ParamOpt(true,
                    WsDataTypes::mString),
            "tgt_id" => new ParamOpt(true,
                    WsDataTypes::mInteger),
            "access" => new ParamOpt(true,
                    WsDataTypes::mInteger)
        );

        $mP = $this->_getParams($mCheck);

        if ($this->_isSuccess()) {
            $mMetaAcl->src_type = $mP["src_type"];
            $mMetaAcl->src_id = $mP["src_id"];
            $mMetaAcl->tgt_type = $mP["tgt_type"];
            $mMetaAcl->tgt_id = $mP["tgt_id"];
            $mMetaAcl->access = $mP["access"];
            $mMetaAcl->insert($this->_result);
        }
    }

}

WsDatasourcesAcl::getInstance()->run();
