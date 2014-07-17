<?php

require_once("../functions.php");
dieIfSessionExpired();

$m = array(); //Messages, notices, errors
$v = 1; //Return status 1=success,-1=error
$r = array();

$mDb = db();

if (!isset($_GET["t"])) {
    $v = -1;
    $m[] = "Error: No adm1 table supplied";
}

if (!isset($_GET["a0"])) {
    $v = -1;
    $m[] = "Error: No adm0 argument supplied";
} else {
    if (!isEscaped($_GET["a0"])) {
        $_GET["a0"] = $mDb->real_escape_string($_GET["a0"]);
    }
}

if ($v == 1) {
    $mSql = "SELECT adm1, minx, miny, maxx, maxy FROM " . $_GET["t"] . " WHERE adm0='" . $_GET["a0"] . "' ORDER BY adm1 ASC";
    if ($result = $mDb->query($mSql)) {
        $r["d"] = array();
        $i = 0;
        while ($obj = $result->fetch_object()) {
            $r["d"][] = $obj;
            $i++;
        }
        if ($i == 0) {
            $v = -1;
            $m[] = "Error: No data in table";
            $m[] = $mSql;
        } else {
            $v = 1;
            $m[] = "Notice: Successfully loaded data";
        };
        $result->close();
    } else {
        $v = -1;
        $r["m"] = "Error: " . mysqli_error($mDb);
    }
}
dbc($mDb);

$r["s"] = $v;
$r["m"] = $m;
echo json_encode($r);