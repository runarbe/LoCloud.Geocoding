<?php
$mZipFile = "";

if ($mDir = opendir(".")) {
    while (false !== ($mConfigPHPFile = readdir($mDir))) {
        if (pathinfo($mConfigPHPFile,PATHINFO_EXTENSION) == "zip") {
            $mZipFile = $mConfigPHPFile;
            break;
        }
    }
    closedir($mDir);
}

$zipArchive = new ZipArchive();
$result = $zipArchive->open($mZipFile);
if ($result === TRUE) {
    $zipArchive ->extractTo(".");
    $zipArchive ->close();
    echo "Success";
} else {
    echo "Error";
}