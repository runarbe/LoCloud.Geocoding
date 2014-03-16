<?php

require_once("../functions.php");

/**
 * Initialize site
 */
function Initialize() {
    
    // Create the database as per values in config.php
    dbcreate();

    $mDb = db();
    $mSql = file_get_contents("./sql/schema.sql");
    $mStatus = dbqmulti($mDb, $mSql);

    $mSql = file_get_contents("./sql/demo-source.sql");
    $mStatus = dbqmulti($mDb, $mSql);

    $mSql = file_get_contents("./sql/demo-searchdb.sql");
    $mStatus = dbqmulti($mDb, $mSql);

    dbc($mDb);

    if ($mStatus == true) {
        echo "success";
    } else {
        echo "failure, see log";
    }
}

Initialize();

?>
