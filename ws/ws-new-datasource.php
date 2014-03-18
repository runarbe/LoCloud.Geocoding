<?php

require_once("../functions.php");
dieIfSessionExpired();

$r = new WsRetObj();

$mParams = array(
    "ds_title" => new WsParamOptions(true),
    "ds_table" => new WsParamOptions(true),
    "ds_col_pk" => new WsParamOptions(true),
    "ds_col_name" => new WsParamOptions(true),
    "ds_srs" => new WsParamOptions(true),
    "ds_col_puri" => new WsParamOptions(false),
    "ds_coord_prec" => new WsParamOptions(false),
    "ds_col_x" => new WsParamOptions(false),
    "ds_col_y" => new WsParamOptions(false),
    "ds_col_adm0" => new WsParamOptions(false),
    "ds_col_adm1" => new WsParamOptions(false),
    "ds_col_cat" => new WsParamOptions(false),
    "ds_col_image" => new WsParamOptions(false),
    "ds_col_url" => new WsParamOptions(false)
);

checkWsParameters($mParams, $r);

//$mParams[] = new WsParamOptions("");

if ($r->v == WsStatus::success) {

    $mMDS = new MetaDatasources();
    $mMDS->ds_title = $mParams["ds_title"];
    $mMDS->ds_table = $mParams["ds_table"];
    $mMDS->ds_col_pk = $mParams["ds_col_pk"];
    $mMDS->ds_col_name = $mParams["ds_col_name"];
    $mMDS->ds_srs = $mParams["ds_srs"];
    $mMDS->ds_coord_prec = $mParams["ds_coord_prec"];
    $mMDS->ds_col_adm0 = $mParams["ds_col_adm0"];
    $mMDS->ds_col_adm1 = $mParams["ds_col_adm1"];
    $mMDS->ds_col_cat = $mParams["ds_col_cat"];
    $mMDS->ds_col_x = $mParams["ds_col_x"];
    $mMDS->ds_col_y = $mParams["ds_col_y"];
    $mMDS->ds_col_image = $mParams["ds_col_image"];
    $mMDS->ds_col_url = $mParams["ds_col_url"];
    $mMDS->ds_col_puri = $mParams["ds_col_puri"];

    $mSql = $mMDS->Insert();
    $mDb = db();

    // Insert record
    if (!$res = $mDb->query($mSql)) {
        $r->addMsg(WsErrors::sqlError, mysqli_error($mDb));
        $r->addMsg(WsErrors::sqlStatement, $mSql);
        $r->v = WsStatus::failure;
    } else {
        if (!$mDb->affected_rows > 0) {
            $r->addMsg(WsErrors::noResults);
            $r->addMsg(WsErrors::sqlStatement, $mSql);
            $r->v(WsStatus::failure);
        } else {
            $r->addMsg(WsErrors::success);
        }
    }

    // Generate target name

    $mCurrentId = mysqli_insert_id($mDb);
    $mTargetName = "ds" . $mCurrentId;

    // Rename table
    $mSql = sprintf("ALTER TABLE %s RENAME TO  %s;", $mParams["ds_table"], $mTargetName);
    if (!$res = $mDb->query($mSql)) {
        $r->setFailure(WsErrors::sqlError, mysqli_error($mDb));
        $r->addMsg(WsErrors::sqlStatement, $mSql);
    } else {
        $r->setSuccess(WsErrors::success);
    }

    // Update record in meta_datasources
    $mSql = sprintf("UPDATE meta_datasources SET ds_table='%s' WHERE id=%s", $mTargetName, $mCurrentId);
    if (!$res = $mDb->query($mSql)) {
        $r->setFailure(WsErrors::sqlError, mysqli_error($mDb));
        $r->addMsg(WsErrors::sqlStatement, $mSql);
    } else {
        $r->setSuccess(WsErrors::success);
        $r->addData($mTargetName, "table");
    }
}
echo $r->getResult();
?>