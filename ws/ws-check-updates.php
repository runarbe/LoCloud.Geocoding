<?php

include ("../functions.php");

function WsCheckUpdates() {

    $mWsRetObj = new WsRetObj();

    if (!isLoggedIn(WsUserLevel::Admin)) {
        $mWsRetObj->setFailure(WsErrors::adminRequired, getFn(__FILE__));
    } else {

        $mLatestVersion = file_get_contents(sprintf("%s/version.txt", LgmsConfig::updateurl));

        if (version_compare($mLatestVersion, LgmsConfig::app_version, ">")) {
            $mWsRetObj->setFailure(WsErrors::updateAvailable, sprintf("%s-->%s", LgmsConfig::app_version, $mLatestVersion));
            $mWsRetObj->addData($mLatestVersion);
        } else {
            $mWsRetObj->setSuccess(WsErrors::installationUpToDate, LgmsConfig::app_version);
        }
    }

    return $mWsRetObj;
}

$mWs = WsCheckUpdates();
$mWs->outputResult();
?>