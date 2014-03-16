<?php

require_once("../functions.php");

dieIfSessionExpired();
dieIfInsufficientElevation(1);

$r = new WsRetObj();

$mParams = array(
    "t" => new WsParamOptions("t"),
);

checkWsParameters($mParams, $r);

if ($r->v === WsStatus::success) {
    /*
     * Create database connection
     */
    $mDb = db();

    /*
     * Select metadata for table
     */
    $mSql = "SELECT * FROM meta_datasources WHERE ds_table = '" . $mParams["t"] . "';";
    if ($result = $mDb->query($mSql)) {
        if ($obj = $result->fetch_object()) {

            /*
             * Add index on fk_ds_id column in match table
             */
            if ($obj->ds_col_pk != "") {
                $mSql = "CREATE INDEX idx_" . $mParams["t"] . "_pk ON " . $mParams["t"] . " (" . $obj->ds_col_pk . ")";
                if ($result = $mDb->query($mSql)) {
                    $r->setSuccess("Notice: successfully created index pk");
                } else {
                    $r->setFailure("Error: couldn't create index pk.", "SQL error: " . mysqli_error($mDb), false);
                }
            }

            /*
             * Add index on adm0 area
             */
            if ($obj->ds_col_adm0 != "") {
                $mSql = "CREATE INDEX idx_" . $_GET["t"] . "_adm0 ON " . $_GET["t"] . "(" . $obj->ds_col_adm0 . ")";
                if ($result = $mDb->query($mSql)) {
                    $r->setSuccess("Notice: successfully created index adm0.");
                } else {
                    $r->setFailure("Error: couldn't create index adm0.", "SQL error: " . mysqli_error($mDb));
                }
            }

            /*
             * Add index on adm1 area
             */
            if ($obj->ds_col_adm1 != "") {
                $mSql = "CREATE INDEX idx_" . $_GET["t"] . "_adm1 ON " . $_GET["t"] . "(" . $obj->ds_col_adm1 . ")";
                if ($result = $mDb->query($mSql)) {
                    $r->setSuccess("Notice: successfully created index adm1");
                } else {
                    $r->setFailure("Error: couldn't create index adm1", "SQL error: " . mysqli_error($mDb));
                }
            }

            /*
             * Add index on category
             */
            if (isset($obj->ds_col_cat) && $obj->ds_col_cat != "") {
                $mSql = "CREATE INDEX idx_" . $_GET["t"] . "_cat ON " . $_GET["t"] . "(" . $obj->ds_col_cat . ")";
                if ($result = $mDb->query($mSql)) {
                    $r->setSuccess("Notice: successfully created index cat");
                } else {
                    $r->setFailure("Error: couldn't create index cat", "SQL error: " . mysqli_error($mDb));
                }
            }

            /*
             * Adding adm0 lookup table
             */
            if ($obj->ds_col_adm0 != "") {
                $mSql = "CREATE TABLE ds" . $obj->id . "_adm0 as SELECT DISTINCT " . $obj->ds_col_adm0 . " AS adm0, MIN(" . $obj->ds_col_x . ") AS minx, MIN(" . $obj->ds_col_y . ") AS miny, MAX(" . $obj->ds_col_x . ") AS maxx, MAX(" . $obj->ds_col_y . ") AS maxy FROM " . $obj->ds_table . " GROUP BY " . $obj->ds_col_adm0;
                if ($result = $mDb->query($mSql)) {
                    $r->setSuccess("Notice: Successfully added adm0 table");
                } else {
                    $r->setFailure("Error: could not create table ds*_adm0.", "SQL error: " . mysqli_error($mDb));
                }
            } else {
                $r->addMsg("Notice: Datasource does not include 1st level area division.");
            }

            /*
             * Adding adm1 lookup table
             */
            if ($obj->ds_col_adm0 != "" && $obj->ds_col_adm1 != "") {
                $mSql = "CREATE TABLE ds" . $obj->id . "_adm1 as SELECT DISTINCT " . $obj->ds_col_adm0 . " AS adm0, " . $obj->ds_col_adm1 . " AS adm1, MIN(" . $obj->ds_col_x . ") AS minx, MIN(" . $obj->ds_col_y . ") AS miny, MAX(" . $obj->ds_col_x . ") AS maxx, MAX(" . $obj->ds_col_y . ") AS maxy FROM " . $obj->ds_table . " GROUP BY " . $obj->ds_col_adm0 . ", " . $obj->ds_col_adm1;
                if ($result = $mDb->query($mSql)) {
                    $r->setSuccess("Successfully added adm1 table");
                } else {
                    $r->setFailure("Error when creating adm1 table", mysqli_error($mDb));
                }
            } else {
                $r->addMsg("Notice: This data source does not include second level area division");
            }

            /*
             * Adding category lookup table
             */
            if ($obj->ds_col_cat != "") {
                $mSql = "CREATE TABLE ds" . $obj->id . "_cat as SELECT DISTINCT " . $obj->ds_col_cat . " AS category, MIN(" . $obj->ds_col_x . ") AS minx, MIN(" . $obj->ds_col_y . ") AS miny, MAX(" . $obj->ds_col_x . ") AS maxx, MAX(" . $obj->ds_col_y . ") AS maxy FROM " . $obj->ds_table . " GROUP BY " . $obj->ds_col_cat;
                if ($result = $mDb->query($mSql)) {
                    $r->setSuccess("Successfully added ds*_cat table");
                } else {
                    $r->setFailure("Error when creating ds*_cat table", mysqli_error($mDb));
                }
            } else {
                $r->addMsg("Notice: This data source does not include categories");
            }

            /*
             * Create match table
             */
            $mSql = "CREATE TABLE ds" . $obj->id . "_match LIKE meta_match_template";
            if ($result = $mDb->query($mSql)) {
                $r->setSuccess("Notice: successfully added ds*_match table");
            } else {
                $r->setFailure("Error: could not create ds*_match table.", "SQL error: " . mysqli_error($mDb));
            }
        } else {
            $r->setFailure("Error: no metadata entry for table" . $mParam["t"]);
        }
    } else {
        $r->setFailure("Error: could not select metadata: ", mysqli_error($mDb));
    }

    dbc($mDb);
}

echo $r->getResult();
?>