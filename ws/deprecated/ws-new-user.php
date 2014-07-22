<?php

require_once("../functions.php");
dieIfSessionExpired();
dieIfInsufficientElevation(UserLevels::SuperAdmin);

$r = new WsRetObj();

$mParams = array(
    "usr" => new ParamOpt(),
    "pwd" => new ParamOpt(),
    "level" => new ParamOpt(null,
            null,
            UserLevels::User)
);

checkWSParameters($mParams,
        $r);

if ($r->v == WsStatus::success) {
    $mSql = sprintf(
            "INSERT INTO meta_usr (usr, pwd, level) VALUES ('%s','%s',%d);",
            $mParams["usr"],
            $mParams["pwd"],
            $mParams["level"]
    );
    $mDb = db();

    if (!$res = $mDb->query($mSql)) {
        $r->addMsg(ErrorMsgs::sqlError,
                $mDb->error);
        $r->addMsg(ErrorMsgs::sqlStatement,
                $mSql);
        $r->v = WsStatus::failure;
    } else {
        if (!$mDb->affected_rows > 0) {
            $r->addMsg(ErrorMsgs::noResults);
            $r->addMsg(ErrorMsgs::sqlStatement,
                    $mSql);
            $r->v(WsStatus::failure);
        } else {
            $r->addMsg(ErrorMsgs::success);
        }
    }
}
echo $r->getResult();
