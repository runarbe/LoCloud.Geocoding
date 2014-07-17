<?php

require_once("../functions.php");

class WSGridDataSources extends GcWebService implements iWebService {

    public function execute() {

        $mCheck = array(
            "cmd" => new ParamOpt(true,
                    WsDataTypes::mString)
        );
        
        $mParams = $this->_getParams($mCheck);

        if ($this->isSuccess()) {

            switch ($mParams["cmd"]) {
                case "get-records":
                    $this->getRecords();
                    break;
                case "save-records":
                    $this->saveRecords();
                    break;
                case "delete-records":
                    $this->deleteDatasource();
                    break;
                case "get-datasource-field-names":
                    $this->getDataSourceFieldNames();
                    break;
                default:
                    $this->operationNotSupported();
                    break;
            }
        }
        echo $this->_result->getResult();
    }

    private function saveRecords() {

        $mMetaDatasources = new MetaDatasources;

        $mCheck2 = array(
            "changed" => new ParamOpt(true,
                    WsDataTypes::mArray)
        );

        $mP = $this->_getParams($mCheck2);

        if ($this->isSuccess()) {
            foreach ($mP["changed"] as $mChange) {
                if (MetaAcl::UsrCanEditDatasource(cUsr(),
                                $mChange["id"]) || isLoggedIn(UserLevels::SuperAdmin)) {

                    $mMetaDatasources->id = $mChange["id"];
                    $mMetaDatasources->ds_title = isset($mChange["ds_title"]) ?
                            Db::esc($mChange["ds_title"]) : null;
                    $mMetaDatasources->ds_col_pk = isset($mChange["ds_col_pk"]) ?
                            Db::esc($mChange["ds_col_pk"]) : null;
                    $mMetaDatasources->ds_col_puri = isset($mChange["ds_col_puri"]) ?
                            Db::esc($mChange["ds_col_puri"]) : null;
                    $mMetaDatasources->ds_col_name = isset($mChange["ds_col_name"]) ?
                            Db::esc($mChange["ds_col_name"]) : null;
                    $mMetaDatasources->ds_col_image = isset($mChange["ds_col_image"]) ?
                            Db::esc($mChange["ds_col_image"]) : null;
                    $mMetaDatasources->ds_col_url = isset($mChange["ds_col_url"]) ?
                            Db::esc($mChange["ds_col_url"]) : null;
                    $mMetaDatasources->ds_col_x = isset($mChange["ds_col_x"]) ?
                            Db::esc($mChange["ds_col_x"]) : null;
                    $mMetaDatasources->ds_col_y = isset($mChange["ds_col_y"]) ?
                            Db::esc($mChange["ds_col_y"]) : null;
                    $mMetaDatasources->update($this->_result);
                } else {
                    $this->_result->setFailure(ErrorMsgs::insufficientRights("update",
                                    "data source"));
                }
            }
        }
    }

    private function getRecords() {

        $mMetaDatasources = new MetaDatasources();

        $mParams2 = array(
            "id" => new ParamOpt(false,
                    WsDataTypes::mInteger),
            "offset" => new ParamOpt(false,
                    WsDataTypes::mInteger),
            "limit" => new ParamOpt(false,
                    WsDataTypes::mInteger)
        );

        if (checkWSParameters($mParams2,
                        $this->_result) == WsStatus::success) {

            if (null !== ($mAclArray = $mMetaDatasources->selectMap(null,
                    "t.ds_title",
                    $mParams2["limit"],
                    $mParams2["offset"],
                    $this->_result,
                    null,
                    null,
                    "id"))) {
                
            } else {
                $this->_result->setFailure(ErrorMsgs::noResults);
            }
        }
    }

    private function deleteDatasource() {

        $mParams2 = filter_input_array(INPUT_POST,
                array(
            "selected" => array(
                "filter" => FILTER_DEFAULT,
                "flags" => FILTER_REQUIRE_ARRAY
            )
        ));

        if (is_array($mParams2["selected"])) {
            foreach ($mParams2["selected"] as $mSelectedID) {
                if (MetaAcl::UsrCanDeleteDatasource(cUsr(),
                                $mSelectedID) || isLoggedIn(UserLevels::SuperAdmin)) {
                    MetaDatasources::deleteDatasource($mSelectedID, $this->_result);
//                    $mMetaDatasources->id = $mSelectedID;
//                    $mMetaDatasources->delete($this->_result);
                } else {
                    $this->_result->setFailure(ErrorMsgs::insufficientRights("delete",
                                    "data source"));
                }
            }
        }
    }

    private function getDataSourceFieldNames() {
        $mMetaDatasources = new MetaDatasources();
        $mParams2 = filter_input_array(
                INPUT_POST,
                array("id" => FILTER_VALIDATE_INT));
        if ($mParams2["id"] !== null) {
            $mRes = $mMetaDatasources->getDatasourceFieldNames($mParams2["id"]);
            $this->_result->setRecords($mRes);
        }
    }

    /**
     * Returns a new instance of this class
     * @return \WSGridDataSources
     */
    public static function getInstance() {
        return new WSGridDataSources();
    }

}

WSGridDataSources::getInstance()->execute();
