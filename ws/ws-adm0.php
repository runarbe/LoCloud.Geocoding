<?php
/*
 * Web service to load adm0 areas
 * @author: Runar Bergheim
 * @date: 02.07.2013
 */
require_once("../functions.php");
dieIfSessionExpired();

$m = array(); //Messages, notices, errors
$v = 1; //Return status 1=success,-1=error
$r = array();

if (!isset($_GET["t"])) {
    $v = -1;
    $m[] = "No table selected";
}

if ($v == 1) {
    $mDb = db();
    $mSql = "SELECT adm0, minx, miny, maxx, maxy FROM " . $_GET["t"] . " ORDER BY adm0 ASC";
    if ($result = $mDb->query($mSql)) {
        $r["d"] = array();
        $i = 0;
        while ($obj = $result->fetch_object()) {
            $r["d"][] = $obj;
            $i++;
        }
        if ($i == 0) {
            $v = -1;
            $m[] = "No data in table";
        } else {
            $v = 1;
            $m[] = "Successfully loaded data";
        };
        $result->close();
    } else {
        $v = -1;
        $r["m"] = "Error: " . mysqli_error($mDb);
    }

    dbc($mDb);
}

$r["s"] = $v;
$r["m"] = $m;
echo json_encode($r);
?>