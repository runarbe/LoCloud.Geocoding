<?php

require_once("../functions.php");
dieIfSessionExpired();

/*
 * Declare temporary variables to hold accumulated data
 */


$m = array(); //Mesages, notices, errors
$v = WsStatus::success; //Return status 1=success,-1=error
$d = array(); // To hold data
$alt = array(); // To hold alternate locations - not used - may be deleted

$r = new WsRetObj();

/*
 * Declare variables to hold parameters and control program flow
 */
$a0 = ""; // Filter adm0
$a1 = ""; // Filter adm1
$c = ""; // Filter category
$w = array(); // Where expressions, depending on filter arguments
$ob = ""; // Order by expression - only to be included once filters are applied

$includeWhereClause = false;
$includeOrderByClause = false;

$mDb = db();

$mParams = array(
    "t" => new WsParamOptions(true),
    "dsID" => new WsParamOptions(true),
    "idc" => new WsParamOptions(true),
    "nc" => new WsParamOptions(true),
    "xc" => new WsParamOptions(false, WsDataTypes::mNull, "null"),
    "yc" => new WsParamOptions(false, WsDataTypes::mNull, "null"),
    "uc" => new WsParamOptions(false, WsDataTypes::mNull, "null"),
    "ic" => new WsParamOptions(false, WsDataTypes::mNull, "null"),
    "limit" => new WsParamOptions(false, WsDataTypes::mNull, "10"),
    "offset" => new WsParamOptions(false, WsDataTypes::mNull, "0"),
    "a0_c" => new WsParamOptions(false, WsDataTypes::mNull, "null"),
    "a0" => new WsParamOptions(false, WsDataTypes::mNull, null),
    "a1_c" => new WsParamOptions(false, WsDataTypes::mNull, "null"),
    "a1" => new WsParamOptions(false, WsDataTypes::mNull, null),
    "c_c" => new WsParamOptions(false, WsDataTypes::mNull, null),
    "c" => new WsParamOptions(false, WsDataTypes::mNull, null),
    "p" => new WsParamOptions(false, WsDataTypes::mNull, null)
);


// Check if parameters are set
checkWsParameters($mParams, $r);

if (isset($mParams["a0_c"]) && $mParams["a0"] != "") {
    $includeWhereClause = true;
    $includeOrderByClause = true;
    if (!isEscaped($mParams["a0"])) {
        $mParams["a0"] = $mDb->real_escape_string($mParams["a0"]);
    }
    $w[] = $mParams["a0_c"] . "='" . $mParams["a0"] . "'";
}

if (isset($mParams["a1_c"]) && $mParams["a1"] != "") {
    $includeWhereClause = true;
    $includeOrderByClause = true;
    if (!isEscaped($mParams["a1"])) {
        $mParams["a1"] = $mDb->real_escape_string($mParams["a1"]);
    }
    $w[] = $mParams["a1_c"] . "='" . $mParams["a1"] . "'";
}

if (is_numeric($mParams["p"])) {
    $includeWhereClause = true;
    $w[] = "gc_probability=" . $mParams["p"];
}

// Add category where clause
if ($mParams["c_c"] !== null && $mParams["c"] !== null) {
    $includeWhereClause = true;
    $includeOrderByClause = true;
    if (!isEscaped($mParams["c"])) {
        $mParams["c"] = $mDb->real_escape_string($mParams["c"]);
    }
    $w[] = $mParams["c_c"] . "='" . $mParams["c"] . "'";
}

if (isset($_SESSION["usr_id"])) {
    $j = " AND (jt.gc_usr_id=" . $_SESSION["usr_id"] . ") ";
}

if ($includeWhereClause) {
    $w = " WHERE " . implode($w, " AND ");
} else {
    $w = "";
}

if ($includeOrderByClause) {
    $ob = " ORDER BY gc_name ASC, " . $mParams["nc"] . " ASC ";
}

if ($v === 1) {

    /*
     * Determine the name of the table to join to the datasource based on the
     * datasource id and the naming rules
     */
    $mJoinTableName = "ds" . $mParams["dsID"] . "_match";

    /*
     * Construct select statement
     */
    $mSql = sprintf("SELECT COALESCE(gc_name, %s) as _nc, %s as _x, %s as _y, ds.%s as _id, %s as _ic, %s as _uc, ds.*, jt.* FROM %s ds LEFT JOIN %s jt ON jt.fk_ds_id = ds.%s %s %s %s LIMIT %s OFFSET %s;", $mParams["nc"], $mParams["xc"], $mParams["yc"], $mParams["idc"], $mParams["ic"], $mParams["uc"], $mParams["t"], $mJoinTableName, $mParams["idc"], $j, $w, $ob, $mParams["limit"], $mParams["offset"]
    );

    logIt($mSql);

    $result = $mDb->query($mSql);
    if ($result) {
        $r->setSuccess(WsErrors::success);
        while ($obj = $result->fetch_object()) {
            $obj->gc_alternates = array();
            $d[(string) $obj->_id] = $obj;
        };
        $result->close();

        if (count($d) > 0) {
            /*
             * The main loop only takes coordinates for the current user and
             * he/she may not have geocoding for the item. In order to load
             * existing geocoding added by other users, do a loop through the batch
             * of search results and select any existing geocodes.
             * 
             * NB! This function costs an extra and potentially costly query and it
             * is therefore necessary to assess alternative methods in the long run
             */
            $mKeys = array_keys($d);
            if (is_string($mKeys[0])) {
                $mKeys = "'" . implode($mKeys, "','") . "'";
            } else {
                $mKeys = implode($mKeys, ",");
            }
            $isql = "SELECT fk_ds_id, gc_lat, gc_lon, gc_probability FROM " . $mJoinTableName . " WHERE gc_usr_id != " . $_SESSION["usr_id"] . " AND fk_ds_id in (" . $mKeys . ") ORDER BY gc_probability ASC";
            logIt($isql);
            $iresult = $mDb->query($isql);
            if ($iresult) {
                logIt("got results");
                while ($obj2 = $iresult->fetch_object()) {
                    $d[(string) $obj2->fk_ds_id]->gc_alternates[] = $obj2;

                    /*
                     * If the current user does not have geocoding for the item
                     * take the coordinates of the best rated existing geocoding
                     * if any
                     */
                    if (!is_numeric($d[(string) $obj2->fk_ds_id]->gc_lon) ||
                            !is_numeric($d[(string) $obj2->fk_ds_id]->gc_lat)) {
                        $d[(string) $obj2->fk_ds_id]->gc_lon = $obj2->gc_lon;
                        $d[(string) $obj2->fk_ds_id]->gc_lat = $obj2->gc_lat;
                        $d[(string) $obj2->fk_ds_id]->gc_probability = $obj2->gc_probability;
                    }
                }
                $iresult->close();
                $r->d = array_values($d);
            } else {
                /*
                 * If something went wrong during the SQL query for additional
                 * geocoded points from other users
                 */
                $r->setFailure(WsErrors::sqlError . mysqli_error($mDb), WsErrors::sqlStatement . $isql);
            }
        } else {
            $r->setSuccess(WsErrors::noResults);
        }
    } else {
        /*
         * If there was an error loading the first set of items
         */
        $r->setFailure(WsErrors::failure, WsErrors::sqlError . mysqli_error($mDb));
        $r->addMsg(WsErrors::sqlStatement . $mSql);
    }
} else {
    /*
     * If the query could noe be executed add status code and return errors
     */
    $r->setFailure(WsErrors::summary);
}
dbc($mDb);

echo $r->getResult();
?>