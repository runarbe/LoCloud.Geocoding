<?php

require_once("../functions.php");
dieIfSessionExpired();

$r = new WsRetObj();

$mParams = array(
    "id" => new WsParamOptions(false, WsDataTypes::mNull, null)
);

checkWsParameters($mParams, $r);

$mDb = db();
if ($mParams["id"] === null) {
    $mSql = "SELECT * FROM meta_datasources ORDER BY ds_title ASC";
    $r->setSuccess("Notice: no datasource id specified");
} else {
    $mSql = "SELECT * FROM meta_datasources WHERE id = " . $mParams["id"] . " ORDER BY ds_title ASC";
}

if (false !== ($result = $mDb->query($mSql))) {
    $r->setSuccess();
    while ($obj = $result->fetch_object()) {
        $r->d[] = $obj;
    };
    $result->close();
} else {
    $r->setFailure($mSql, mysqli_error($mDb));
}
dbc($mDb);

echo $r->getResult();
?>