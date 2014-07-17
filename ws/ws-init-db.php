<?php

require_once("../functions.php");

$mWsRetObj = new WsRetObj();

$mAppTitle = inp("app_title");
$mDb = inp("db");
$mDbHost = inp("db_host");
$mDbUsr = inp("db_usr");
$mDbPwd = inp("db_pwd");
$mOverwrite = inp("overwrite");

if (file_exists("../config.php") && $mOverwrite != 1) {
    $mWsRetObj->setFailure();
    $mWsRetObj->addMsg("File already exists...");
} else if (testDb($mDbHost, $mDbUsr, $mDbPwd)) {
    $mWsRetObj->addMsg("Overwriting existing config.php file...");
    if ($mAppTitle && $mDb && $mDbHost && $mDbUsr && $mDbPwd) {
        $mFormat = file_get_contents("../config-template.php");
        $mConfigPHP = sprintf($mFormat, $mAppTitle, $mDb, $mDbHost, $mDbUsr, $mDbPwd);
        $mWsRetObj->setSuccess();
        file_put_contents("../config.php", $mConfigPHP, LOCK_EX);
    } else {
        $mWsRetObj->addMsg(var_export(get_object_vars($_POST), true));
        $mWsRetObj->setFailure();
    }
} else {
    $mWsRetObj->addMsg("Could not connect to database");
    $mWsRetObj->setSuccess();
}


echo $mWsRetObj->getResult();
?>
