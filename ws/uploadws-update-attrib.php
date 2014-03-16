<?php

require_once("../functions.php");
dieIfSessionExpired();

$r = array();
$m = array();
$v = 1;
$mDb = db();

if (!isset($_GET["dsID"])) {
    $m[] = "Error: no dataset ID specified.";
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
    if (!isEscaped($_GET["name"])) {
        $_GET["name"] = $mDb->real_escape_string($_GET["name"]);
    }
}

if (!is_numeric($_GET["probability"])) {
    $m[] = "Notice: no probability specified. The default value 3 will be used.";
    $_GET["probability"] = 3;
};

if ($v == 1) {
    /*
     * Determine the name of the table to update based on the datasource id and the naming rules
     */
    $mTableName = "ds" . $_GET["dsID"] . "_match";
    
    /*
     * First attempt to insert a new record
     * This will fail if the combination of the record id and the user id already exists
     */
    $mSql = "INSERT INTO " . $mTableName . "  (fk_ds_id, gc_name, gc_name2, gc_lon, gc_lat,gc_probability, gc_geom, gc_fieldchanges, gc_usr_id) VALUES (" .
            $_GET["id"] . "," .
            "'".$_GET["name"] . "'," .
            "null," .
            $_GET["lon"] . "," .
            $_GET["lat"] . "," .
            $_GET["probability"] . "," .
            "null," .
            "null," .
            $_SESSION["usr_id"] .
            ");";
    $mDb->query($mSql);
    if ($mDb->affected_rows >= 1) {
        $v = 1;
        $m[] = "Notice: successfully inserted record.";
    } else {
        /*
         * If insert failed, proceed to try an update
         */
        $m[] = "Error: insert failed.";
        $m[] = "SQL error:" . mysqli_error($mDb);
        $m[] = "SQL:" . $mSql;
        $mSql = "UPDATE " . $mTableName . "  SET fk_ds_id=" . $_GET["id"] . ", gc_lon=" . $_GET["lon"] . ", gc_lat=" . $_GET["lat"] . ", gc_timestamp = now(), gc_probability='" . $_GET["probability"] . "', gc_name='" . $_GET["name"] . "', gc_usr_id=" . $_SESSION["usr_id"] . ", gc_fieldchanges=null, gc_geom=null WHERE fk_ds_id=" . $_GET["id"] . " AND gc_usr_id=" . $_SESSION["usr_id"];
        $mDb->query($mSql);
        if ($mDb->affected_rows >= 1) {
            $m[] = "Notice: successfully updated record.";
            $v = 1;
        } else {
            /*
             * If update failed, return error
             */
            $v = -1;
            $m[] = "Error: update failed.";
            $m[] = "SQL error:" . mysqli_error($mDb);
            $m[] = "SQL:" . $mSql;
        }
    }
} else {
    $v = -1;
    $m[] = "Error: please review the error messages and correct your web service call.";
}

dbc($mDb);

$r["s"] = $v;
$r["m"] = $m;

echo json_encode($r);
?>