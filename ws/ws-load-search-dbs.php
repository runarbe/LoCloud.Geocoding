<?php
require_once("../functions.php");
dieIfSessionExpired();
$m = array(); //Mesages, notices, errors
$v = 1; //Return status 1=success,-1=error
$r = array();
$d = array();

$mDb = db();
$mSql = "SELECT * FROM meta_dbsearch ORDER BY sch_title ASC";
if ($result = $mDb->query($mSql)) {
    $v = 1;
    while($obj = $result->fetch_object()) {
        $d[] = $obj;
    };
    $result->close();
} else {
    $v = -1;
    $m[] = $mSql;
    $m[] = mysqli_error($mDb);
}
dbc($mDb);

$r["s"] = $v;
$r["d"] = $d;
$r["m"] = $m;

echo json_encode($r);

?>