<?php
require_once("../functions.php");
dieIfSessionExpired();
dieIfInsufficientElevation(WsUserLevel::SuperAdmin);

$r = new WsRetObj();

$mParams = array(
    "usr" => new WsParamOptions(),
    "pwd" => new WsParamOptions(),
    "level" => new WsParamOptions(null, null, WsUserLevel::User)
);

checkWsParameters($mParams, $r);

if ($r->v == WsStatus::success) {
    $mSql = sprintf(
            "INSERT INTO meta_usr (usr, pwd, level) VALUES ('%s','%s',%d);", $mParams["usr"], $mParams["pwd"], $mParams["level"]
    );
    $mDb = db();

    if (!$res = $mDb->query($mSql)) {
        $r->addMsg(WsErrors::sqlError, $mDb->error);
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
}
echo $r->getResult();
?>