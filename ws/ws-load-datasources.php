<?php

require_once("../functions.php");
dieIfSessionExpired();

$r = new WsRetObj();

$mParams = array(
    "id" => new ParamOpt(false,
            WsDataTypes::mNull,
            null)
);

checkWSParameters($mParams,
        $r);

$mDb = db();
if ($mParams["id"] === null) {
    $mSql = "SELECT DISTINCT md.* FROM meta_datasources md LEFT JOIN meta_usr_datasources_acl acl ON (acl.datasource_id = md.id OR acl.datasource_id = 0) WHERE (acl.usr_id= " . $_SESSION["usr_id"] . ") ORDER BY md.ds_title ASC";
    $r->setSuccess("Notice: no datasource id specified");
} else {
    $mSql = "SELECT DISTINCT md.* FROM meta_datasources md LEFT JOIN meta_usr_ds mud ON mud.fk_meta_datasources_id = md.id WHERE md.id = " . $mParams["id"] . " AND mud.fk_meta_usr_id = " . $_SESSION['usr_id'] . " ORDER BY md.ds_title ASC";
}

if (false !== ($result = $mDb->query($mSql))) {
    $r->setSuccess();
    while ($obj = $result->fetch_object()) {
        $r->d[] = $obj;
    }
    $result->close();
} else {
    $r->setFailure($mSql,
            mysqli_error($mDb));
}
dbc($mDb);

echo $r->getResult();