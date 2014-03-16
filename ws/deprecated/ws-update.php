<?php
require_once("../lib/functions.php");
dieIfSessionExpired();

$r = array();
$m = array();
$v = 1;

if (!isset($_GET["table"])) {
    $m[] = "Error: no table specified.";
    $v = -1;
}

if (!is_numeric($_GET["id"])) {
    $m[] = "Error: no id specified.";
    $v = -1;
}

if (!is_numeric($_GET["lon"])) {
    $m[] = "Error: no x-coordinate/longitude specified.";
    $v = -1;
}

if (!is_numeric($_GET["lat"])) {
    $m[] = "Error: no y-coordinate/latitude specified.";
    $v = -1;
}

if (!isset($_GET["name"])) {
    $m[] = "Error: no name specified.";
    $v = -1;
}

if (!is_numeric($_GET["probability"])) {
    $m[] = "Notice: no probability specified. Default is 2.";
    $_GET["probability"] = 2;
};

if ($v == 1) {
    $mDb = db();
    $mSql = "UPDATE " . $_GET["table"] . "  SET gc_lon='" . $_GET["lon"] . "', gc_lat='" . $_GET["lat"] . "', gc_tstamp = now(), gc_probability='" . $_GET["probability"] . "', gc_name='" . $mDb->real_escape_string( $_GET["name"] ). "' WHERE id=" . $_GET["id"];
    $mDb->query($mSql);
    if ($mDb->affected_rows >= 1) {
        $r["s"] = 1;
    } else {
        $r["s"] = -1;
        $m[] = $mSql;
        $m[] = mysqli_error($mDb);
    }
} else {
    $r["status"] = -1;
    $m[] = $_GET;
}

$r["m"] = $m;

echo json_encode($r);
?>