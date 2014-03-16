<?php

require_once("../functions.php");

$m = array(); //Messages, notices, errors
$v = 1; //Return status 1=success,-1=error
$r = array();
$d = array();
if (!isset($_GET["u"])) {
    $v = -1;
    $m[] = "Error: no username specified.";
}

if (!isset($_GET["p"])) {
    $v = -1;
    $m[] = "Error: no password specified.";
}

if ($v == 1) {
    $mDb = db();
    $mSql = "SELECT * FROM meta_usr WHERE usr = '" . $_GET["u"] . "' AND pwd = '" . $_GET["p"] . "'";
    if ($result = $mDb->query($mSql)) {
        if ($obj = $result->fetch_object()) {
            $d = $obj;
        }
        if (count($d) == 0) {
            $v = -1;
            $m[] = "Error: wrong username/password.";
        } else {
            $v = 1;
            $m[] = "Notice: success.";
            session_start();
            $_SESSION["usr_id"] = $obj->id;
            $_SESSION["usr_level"] = $obj->level;
        };
        $result->close();
    } else {
        $v = -1;
        $r["m"] = "Error: during execution of query " . mysqli_error($mDb);
    }

    dbc($mDb);
}

$r["s"] = $v;
$r["m"] = $m;
$r["d"] = $d;
echo json_encode($r);
?>