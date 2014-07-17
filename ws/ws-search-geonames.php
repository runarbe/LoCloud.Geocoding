<?php

require_once("../functions.php");
dieIfSessionExpired();

$m = array(); //Mesages, notices, errors
$v = 1; //Return status 1=success,-1=error
$r = array();
$whereExp = array();

// Test if bbox is present in request (not mandatory)
if ($_GET["bbox"] != "") {
    $bbox = explode(",", $_GET["bbox"]);
    $whereExp[] = "(longitude BETWEEN " . $bbox[0] . " AND " . $bbox[2] . ") AND (latitude BETWEEN " . $bbox[1] . " AND " . $bbox[3] . ")";
}

// Test if table name is present in request
if ($_GET["t"] == "") {
    $m[] = "Error: no table name was specified.";
    $v = -1;
}

// Test if query expression is present
if ($_GET["q"] == "" ) {
    $m[] = "Notice: no search expression supplied. Using default value %.";
    $_GET["q"] = "%";
    $v = 1;
} else {
    $_GET["q"] = strtolower($_GET["q"]);
    $whereExp[] = "(name LIKE '" . $_GET["q"] . "%' OR asciiname LIKE '" . $_GET["q"] . "%' OR alternatenames LIKE '%" . $_GET["q"] . "%')";
}

if ($v == 1) {

    $mDb = db();

    $mSql = "SELECT * FROM ".$_GET["t"]." WHERE".implode(" AND ", $whereExp)."ORDER BY name ASC LIMIT 10";

    if ($result = $mDb->query($mSql)) {

        $i = 0;
        $r["d"] = array();
        while ($obj = $result->fetch_object()) {
            $r["d"][] = $obj;
            $i++;
        };
        $result->close();
        if ($i == 0) {
            $r["s"] = 1;
            $m[] = "Query did not yield results";
        } else {
            $r["s"] = 1;
            $m[] = "Query successful";
        }
    } else {
        $r["s"] = -1;
        $r["sql"] = $mSql;
        $m[] = mysqli_error($mysqli);
    }
} else {
    $r["s"] = $v;
}

$r["m"] = $m;
echo json_encode($r);
?>