<?php

require_once("../functions.php");

$mW2UIRetObj = new W2UIRetObj();
//dieIfSessionExpired();
//dieIfInsufficientElevation(WsUserLevel::Editor);

$mParams = array(
    "action" => new ParamOpt(true,
            WsDataTypes::mString));

checkWSParameters($mParams,
        $mW2UIRetObj);

if ($mW2UIRetObj->status === "success") {

    checkWSParameters($mParams,
            $mW2UIRetObj);

    if ($mW2UIRetObj->status === "success") {

        switch ($mParams["action"]) {
            case "GetAll":
                $mGroupArray = MetaGroup::GetAll();
                $mW2UIRetObj->SetRecords($mGroupArray);
                break;
            case "Get":
                break;
            case "Upsert":
                $mParams = array(
                    "title" => new ParamOpt(false,
                            WsDataTypes::mString),
                    "description" => new ParamOpt(false,
                            WsDataTypes::mString)
                );

                checkWSParameters($mParams,
                        $mW2UIRetObj);

                $mMetaGroup = new MetaGroup();
                $mMetaGroup->title = $mParams["title"];
                $mMetaGroup->description = $mParams["description"];
                if ($mMetaGroup->insert()) {
                    $mW2UIRetObj->setSuccess("Added group " . $mParams['title']);
                } else {
                    $mW2UIRetObj->setFailure("Could not create group " . $mParams['title']);
                }
                break;
            case "Delete":
                $mParams = array(
                    "id" => new ParamOpt(true,
                            WsDataTypes::mString));
                checkWSParameters($mParams,
                        $mW2UIRetObj);

                if ($mW2UIRetObj->status == "success") {
                    $mMetaGroup = new MetaGroup(array("id" => $mParams["id"]));
                    if ($mMetaGroup->delete()) {
                        $mW2UIRetObj->setSuccess("Deleted group with ID:" . $mParams["id"]);
                    } else {
                        $mW2UIRetObj->setFailure("Could not delete group with ID:" . $mParams["id"]);
                    }
                }
                break;
            default:
                break;
        }
    }
    echo $mW2UIRetObj->getResult();
}