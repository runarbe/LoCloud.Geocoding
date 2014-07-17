<?php

require_once("../functions.php");
dieIfSessionExpired();

$r = new WsRetObj();

$mParams = array(
    "t" => new ParamOpt(true)
);

checkWSParameters($mParams, $r);

if ($r->v === WsStatus::success) {
    $mDb = db();
    $mSql = "SELECT category, minx, miny, maxx, maxy FROM " . $_GET["t"] . " ORDER BY category ASC";
    if (false !== ($result = $mDb->query($mSql))) {
        $i = 0;
        while ($obj = $result->fetch_object()) {
            $r->d[] = $obj;
            $i++;
        }
        if ($i == 0) {
            $r->setFailure("Notice: no records returned from query.");
        } else {
            $r->setSuccess("Notice: successfully loaded records from ds*_cat table");
        };
    } else {
        $r->setFailure("Error: database error during query.");
        $r->setFailure(mysqli_error($mDb), $mSql);
    }
    dbc($mDb);
}

echo $r->getResult();
?>