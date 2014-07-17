<?php

require_once("../functions.php");


$mWsRetObj = new WsRetObj();

$mAppTitle = inp("app_title");
$mDb = inp("db");
$mDbHost = inp("db_host");
$mDbUsr = inp("db_usr");
$mDbPwd = inp("db_pwd");
$mBaseDir = inp("basedir");
$mAppVersion = inp("app_version");
$mOverwrite = inp("overwrite");

if (file_exists("../config.php") && $mOverwrite != 1) {
    $mWsRetObj->setFailure(ErrorMsgs::fileAlreadyExists, getFn(__FILE__));
} else if (testDb($mDbHost, $mDbUsr, $mDbPwd)) {
    $mWsRetObj->addMsg(ErrorMsgs::overwritingFile, getFn(__FILE__));
    if ($mAppTitle && $mDb && $mDbHost && $mDbUsr && $mDbPwd) {
        $mFormat = file_get_contents("../config-template.php");
        $mConfigPHP = sprintf($mFormat, $mAppTitle, $mDb, $mDbHost, $mDbUsr, $mDbPwd, str_replace("\\\\","/", $mBaseDir));
        $mWsRetObj->setSuccess();
        file_put_contents("../config.php", $mConfigPHP, LOCK_EX);
    } else {
        $mWsRetObj->setFailure(ErrorMsgs::reqParamMissing, getR($_POST));
    }
} else {
    $mWsRetObj->setFailure(ErrorMsgs::couldNotConnectToDatabase);
}

echo $mWsRetObj->getResult();