<?php

require_once("../functions.php");
$mW2UIRetObj = new W2UIRetObj();

$mParams = array(
    "cmd" => new ParamOpt(true,
            WsDataTypes::mString)
);

checkWSParameters($mParams,
        $mW2UIRetObj);

if ($mW2UIRetObj->status === "success") {

    $mMetaUsr = new MetaUsr();

    switch ($mParams["cmd"]) {
        case "auto-complete":
            $mParams2 = array(
                "term" => new ParamOpt(true,
                        WsDataTypes::mString)
            );
            if (checkWSParameters($mParams2,
                            $mW2UIRetObj) == WsStatus::success) {
                $mRes = $mMetaUsr->selectMap("usr LIKE '" . $mParams2["term"] . "%'",
                        "usr",
                        10,
                        null,
                        $mW2UIRetObj,
                        "usr",
                        "usr");
                if ($mRes) {
                    echo json_encode($mW2UIRetObj->records);
                }
            }
            return;
        case "get-records":
            $mParams2 = array(
                "offset" => new ParamOpt(false,
                        WsDataTypes::mString),
                "limit" => new ParamOpt(false,
                        WsDataTypes::mString)
            );

            checkWSParameters($mParams2,
                    $mW2UIRetObj);

            if (null !== ($mGroupArray = $mMetaUsr->GetAll($mParams2["limit"],
                    $mParams2["offset"],
                    $mW2UIRetObj))) {
                //$mW2UIRetObj->SetRecords($mGroupArray);
            } else {
                $mW2UIRetObj->setFailure("Didn't get any records");
            }
            break;
        case "add-record":
            dieIfInsufficientElevation(UserLevels::Editor);
            $mParams2 = array(
                "usr" => new ParamOpt(true,
                        WsDataTypes::mString),
                "pwd" => new ParamOpt(true,
                        WsDataTypes::mString),
                "level" => new ParamOpt(true,
                        WsDataTypes::mString)
            );

            if (checkWSParameters($mParams2,
                            $mW2UIRetObj)) {
                $mMetaUsr->usr = $mParams2["usr"];
                $mMetaUsr->pwd = $mParams2["pwd"];
                $mMetaUsr->level = $mParams2["level"];
                $mMetaUsr->insert($mW2UIRetObj);
                MetaAcl::AddUsrToUsrAcl(cUsr(),
                        Db::lastId(),
                        AccessLevels::Owner);
            }
            break;
        case "save-records":
            $mParams2 = array(
                "id" => new ParamOpt(true,
                        WsDataTypes::mInteger),
                "usr" => new ParamOpt(true,
                        WsDataTypes::mString),
                "pwd" => new ParamOpt(true,
                        WsDataTypes::mString),
                "level" => new ParamOpt(true,
                        WsDataTypes::mInteger),
            );

            if (checkWSParameters($mParams2,
                            $mW2UIRetObj) == WsStatus::success) {

                if (MetaAcl::UsrCanEditUsr(cUsr(),
                                $mUsrID) || isLoggedIn(UserLevels::Admin)) {

                    $mMetaUsr->id = $mParams2["id"];
                    $mMetaUsr->usr = $mParams2["usr"];
                    $mMetaUsr->pwd = $mParams2["pwd"];
                    $mMetaUsr->level = $mParams2["level"];
                    $mMetaUsr->update($mW2UIRetObj);
                } else {
                    $mW2UIRetObj->setFailure("Insufficient rights to edit user.");
                }
            }
            break;
        case "delete-records":
            $mParams2 = array(
                "selected" => new ParamOpt(true,
                        WsDataTypes::mArray)
            );
            if (checkWSParameters($mParams2,
                            $mW2UIRetObj) == WsStatus::success) {
                foreach ($mParams2["selected"] as $mUsrID) {
                    if (MetaAcl::UsrCanDeleteUsr(cUsr(),
                                    $mUsrID) || isLoggedIn(UserLevels::Admin)) {
                        $mMetaUsr->id = $mUsrID;
                        $mMetaUsr->delete($mW2UIRetObj);

                        // Delete any left-over ACL entries
                        MetaAcl::DeleteUsrAclEntries($mUsrID);
                    } else {
                        $mW2UIRetObj->setFailure("Insufficient rights to delete user");
                    }
                }
            }
            break;
        default:
            $mW2UIRetObj->setFailure("Action not supported: " . $mParams['cmd']);
            break;
    }
}

$mW2UIRetObj->echoJson();
return;
