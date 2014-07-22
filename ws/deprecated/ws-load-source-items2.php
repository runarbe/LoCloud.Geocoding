<?php
require_once("../lib/functions.php");
dieIfSessionExpired();

$m = array(); //Mesages, notices, errors
$v = 1; //Return status 1=success,-1=error
$r = array();
$a0 = ""; // Filter adm0
$a1 = ""; // Filter adm1
$c = ""; // Filter category
$w = array(); //Where expressions, depending on filter arguments
$includeWhereClause = false;

$mDb = db();

// Test if table name is present
if (!isset($_GET["t"])) {
    $m[] = "Error: no table name supplied.";
    $v = -1;
}

// Test if table name is present
if (!is_numeric($_GET["dsID"])) {
    $m[] = "Error: no datasource ID supplied.";
    $v = -1;
}

// Test if id is present
if (!isset($_GET["idc"])) {
    $m[] = "Error: no id column supplied.";
    $v = -1;
}

if (!isset($_GET["nc"])) {
    $m[] = "Error: no name column specified";
    $v = -1;
}

if (!isset($_GET["xc"])) {
    $m[] = "Notice: No x-column specified. Using default x-value 0";
    $_GET["xc"] = 0;
}

if (!isset($_GET["yc"])) {
    $m[] = "Notice: No y-column specified. Using default y-value 0.";
    $_GET["yc"] = 0;
}

if (!is_numeric($_GET["limit"])) {
    $m[] = "Notice: No limit specified. Using default value 10.";
    $_GET["limit"] = 10; //Default limit = 10;
}

if (!is_numeric($_GET["offset"])) {
    $m[] = "Notice: No offset supplied. Using default value 0.";
    $_GET["offset"] = 0; //Default offset = 0
}

if (isset($_GET["a0_c"]) && $_GET["a0"] != "") {
    $m[] = "Notice: Included filter for first level area division.";
    $includeWhereClause = true;
    if (!isEscaped($_GET["a0"])) {
        $_GET["a0"] = $mDb->real_escape_string($_GET["a0"]);
    }
    $w[] = $_GET["a0_c"] . "='" . $_GET["a0"] . "'";
}

if (isset($_GET["a1_c"]) && $_GET["a1"] != "") {
    $m[] = "Notice: Included filter for second level area division.";
    $includeWhereClause = true;
    if (!isEscaped($_GET["a1"])) {
        $_GET["a1"] = $mDb->real_escape_string($_GET["a1"]);
    }
    $w[] = $_GET["a1_c"] . "='" . $_GET["a1"] . "'";
}

if (is_numeric($_GET["p"])) {
    $m[] = "Notice: Included filter for probability.";
    $includeWhereClause = true;
    $w[] = "gc_probability=" . $_GET["p"];
}

if (isset($_GET["c_c"]) && $_GET["c"] != "") {
    $m[] = "Notice: Included filter for categories.";
    $includeWhereClause = true;
    if (!isEscaped($_GET["c"])) {
        $_GET["c"] = $mDb->real_escape_string($_GET["c"]);
    }
    $w[] = $_GET["c_c"] . "='" . $_GET["c"] . "'";
}

if ($v == 1) {

    /*
     * Determine the name of the table to join to the datasource based on the
     * datasource id and the naming rules
     */
    $mJoinTableName = "ds" . $_GET["dsID"] . "_match";
    

    $mSql = "SELECT *, COALESCE(gc_name,if(" . $_GET["nc"] . "='',CONCAT('Unnamed item #',ds.".$_GET["idc"].")," . $_GET["nc"] . ")) as _nc, " .
            $_GET["xc"] . " as _x, " .
            $_GET["yc"] . " as _y, ds." . $_GET["idc"] . " as _id FROM " . $_GET["t"] .
            " ds LEFT JOIN ". $mJoinTableName . " jt ON jt.fk_ds_id = ds.id";
    if ($includeWhereClause) {
        $mSql .= " WHERE " . implode($w, " AND ");
    }

    $mSql .= " ORDER BY 1 ASC LIMIT " . $_GET["limit"] . " OFFSET " . $_GET["offset"];

    if ($result = $mDb->query($mSql)) {
        $r["s"] = 1;
        $r["d"] = array();
        while ($obj = $result->fetch_object()) {
            $r["d"][] = $obj;
        };
        $result->close();
        $m[] = "Notice: successfully loaded source items.";
    } else {
        $r["s"] = -1;
        $m[] = "Error: SQL error during loading of items.";
        $m[] = "SQL error: ".mysqli_error($mDb);
        $m[] = "SQL: ".$mSql;
    }
} else {
    $r["s"] = $v;
}
dbc($mDb);
$r["m"] = $m;
echo json_encode($r);
?>