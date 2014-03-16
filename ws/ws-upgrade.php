<?php

require_once("../functions.php");
dieIfSessionExpired();

/**
 * Perform upgrade
 * Downloads a 1Mb+ file and extracts it over the current installation, can take 1 minute.
 * 
 * @param string $pVersion Version string, i.e. 1.1.1
 * @return \WsRetObj
 */
function WsUpgrade($pVersion = null) {

    $mWsRetObj = new WsRetObj();

    if (!class_exists('ZipArchive')) {
        $mWsRetObj->setFailure("Class ZipArchive not found.");
        return $mWsRetObj;
    }

    if (!isLoggedIn(WsUserLevel::Admin)) {
        $mWsRetObj->setFailure(WsErrors::adminRequired, getFn(__FILE__));
        return $mWsRetObj;
    }

    if ($pVersion === null) {
        $mWsRetObj->setFailure(WsErrors::reqParamMissing, getFn(__FILE__));
        return $mWsRetObj;
    }

    $mZipFile = dirname(__FILE__) . "/../setup/zip/tmp.zip";

    if (file_exists($mZipFile)) {
        unlink($mZipFile);
        $mWsRetObj->setSuccess("Deleted old zip archive");
    }

    $s = file_put_contents($mZipFile, fopen(sprintf("%s/locloudgc-%s.zip", LgmsConfig::updateurl, $pVersion), 'r'));
    if ($s !== false) {
        $mWsRetObj->setSuccess(sprintf("Downloaded new zip archive to temporary folder, wrote %s bytes", $s));
    };

    $zipArchive = new ZipArchive();

    $s = $zipArchive->open($mZipFile);

    if ($s === true) {
        $mWsRetObj->setSuccess("Successfully opened zip archive.");

        /**
         * Unpack the archive to the basedir
         * Close the archive
         */
        //$s = $zipArchive->extractTo($conf["basepath"]);
        $s = $zipArchive->extractTo("../");

        if ($s === false) {
            $mWsRetObj->setFailure("Failed to extract files from zip archive", getFn(__FILE__));
        } else {
            $mWsRetObj->setSuccess("Successfully unpacked the archive");
            /**
             * Update the version number in the config file
             */
            $mConfigPHPFile = LgmsConfig::basedir . "/config.php";
            //chmod($mConfigPHPFile, 0777);

            $mData = file_get_contents($mConfigPHPFile);
            if ($mData !== false) {
                $mWsRetObj->setSuccess("Successfully opened configuration template");
                $mData = preg_replace('/const app_version = ".*"/', 'const app_version = "' . $pVersion . '"', $mData);
                logIt($mData);

                $s = file_put_contents($mConfigPHPFile, $mData);
                if ($s !== false) {
                    $mWsRetObj->setSuccess(WsErrors::success, getFn(__FILE__));
                } else {
                    $mWsRetObj->setFailure(WsErrors::couldNotWriteToFile, getFn(__FILE__));
                    $mWsRetObj->addMsg($mConfigPHPFile);
                }
            } else {
                $mWsRetObj->setFailure(WsErrors::fileMissingOrInaccessible, getFn(__FILE__));
            }
        }

        $zipArchive->close();
    } else {
        $mWsRetObj->setFailure(WsErrors::fileMissingOrInaccessible, getFn(__FILE__));
        $mWsRetObj->addMsg($mZipFile);
    }

    return $mWsRetObj;
}

$pVersion = inp("pVersion");
$mWs = WsUpgrade($pVersion);

$mWs->outputResult();
?>
