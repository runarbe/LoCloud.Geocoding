<?php

require_once("../functions.php");

dieIfSessionExpired();
dieIfInsufficientElevation(UserLevels::Editor);

$r = new WsRetObj();

$mParams = array(
    "ds_title" => new ParamOpt(true),
    "ds_table" => new ParamOpt(true),
    "ds_col_pk" => new ParamOpt(true),
    "ds_col_name" => new ParamOpt(true),
    "ds_srs" => new ParamOpt(true),
    "ds_col_puri" => new ParamOpt(false),
    "ds_coord_prec" => new ParamOpt(false),
    "ds_col_x" => new ParamOpt(false),
    "ds_col_y" => new ParamOpt(false),
    "ds_col_adm0" => new ParamOpt(false),
    "ds_col_adm1" => new ParamOpt(false),
    "ds_col_cat" => new ParamOpt(false),
    "ds_col_image" => new ParamOpt(false),
    "ds_col_url" => new ParamOpt(false)
);

if (checkWSParameters($mParams,
                $r)) {

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

    if (!$mMDS->insert($r)) {
        if (!$mDb->affected_rows > 0) {
            $r->setFailure(ErrorMsgs::noResults);
        } else {
            $r->setSuccess();
        }
    }

    // Generate target name
    $mCurrentId = Db::lastId();
    $mTargetName = "ds" . $mCurrentId;

    // Rename table
    $mSql = sprintf("ALTER TABLE %s RENAME TO  %s;",
            $mParams["ds_table"],
            $mTargetName);

    if (!$res = Db::query($mSql)) {
        $r->setFailure(ErrorMsgs::sqlError,
                mysqli_error(Db::$conn));
        $r->addMsg(ErrorMsgs::sqlStatement,
                $mSql);
    } else {
        $r->setSuccess(ErrorMsgs::success);
    }

    // Update record in meta_datasources
    $mSql = sprintf("UPDATE meta_datasources SET ds_table='%s' WHERE id=%s",
            $mTargetName,
            $mCurrentId);

    if (!$res = Db::query($mSql)) {
        $r->setFailure(ErrorMsgs::sqlError,
                mysqli_error(Db::$conn));
        $r->addMsg(ErrorMsgs::sqlStatement,
                $mSql);
    } else {
        $r->setSuccess(ErrorMsgs::success);
        $r->addData($mTargetName,
                "table");
    }

    MetaAcl::AddUsrToDatasourceAcl(cUsr(),
            $mCurrentId,
            AccessLevels::Owner);
}

$r->echoJson();