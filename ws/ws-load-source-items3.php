<?php

require_once("../functions.php");

class WsLoadSourceItems extends GcWebService implements iWebService {

    protected function _execute() {
        
        $this->requireSession();

        /*
         * Declare variables to hold parameters and control program flow
         */
        $mWhereClause = array(); // Where expressions, depending on filter arguments
        $mOrderByClause = ""; // Order by expression - only to be included once filters are applied

        $includeWhereClause = false;
        $includeOrderByClause = false;

        $mCheck = array(
            "t" => new ParamOpt(true),
            "dsID" => new ParamOpt(true),
            "idc" => new ParamOpt(true),
            "nc" => new ParamOpt(true),
            "xc" => new ParamOpt(false,
                    WsDataTypes::mString,
                    'null'),
            "yc" => new ParamOpt(false,
                    WsDataTypes::mString,
                    'null'),
            "uc" => new ParamOpt(true,
                    WsDataTypes::mString,
                    'null'),
            "ic" => new ParamOpt(false,
                    WsDataTypes::mString,
                    'null'),
            "limit" => new ParamOpt(false,
                    WsDataTypes::mNull,
                    "10"),
            "offset" => new ParamOpt(false,
                    WsDataTypes::mNull,
                    "0"),
            "a0_c" => new ParamOpt(false,
                    WsDataTypes::mNull,
                    "null"),
            "a0" => new ParamOpt(false,
                    WsDataTypes::mNull,
                    null),
            "a1_c" => new ParamOpt(false,
                    WsDataTypes::mNull,
                    "null"),
            "a1" => new ParamOpt(false,
                    WsDataTypes::mNull,
                    null),
            "c_c" => new ParamOpt(false,
                    WsDataTypes::mNull,
                    null),
            "c" => new ParamOpt(false,
                    WsDataTypes::mString,
                    null),
            "p" => new ParamOpt(false,
                    WsDataTypes::mNull,
                    null)
        );

        // Check if parameters are set
        $mP = $this->_getParams($mCheck);
        logIt($mP);
        if ($this->_isSuccess()) {

            if (isset($mP["a0_c"]) && $mP["a0"] != "") {
                $includeWhereClause = true;
                $includeOrderByClause = true;
                if (!isEscaped($mP["a0"])) {
                    $mP["a0"] = Db::esc($mP["a0"]);
                }
                $mWhereClause[] = $mP["a0_c"] . "='" . $mP["a0"] . "'";
            }

            if (isset($mP["a1_c"]) && $mP["a1"] != "") {
                $includeWhereClause = true;
                $includeOrderByClause = true;
                if (!isEscaped($mP["a1"])) {
                    $mP["a1"] = Db::esc($mP["a1"]);
                }
                $mWhereClause[] = $mP["a1_c"] . "='" . $mP["a1"] . "'";
            }

            if (is_numeric($mP["p"])) {
                $includeWhereClause = true;
                $mWhereClause[] = "gc_probability=" . $mP["p"];
            }

            // Add category where clause
            if ($mP["c_c"] !== null && $mP["c"] !== null) {
                $includeWhereClause = true;
                $includeOrderByClause = true;
                if (!isEscaped($mP["c"])) {
                    $mP["c"] = Db::esc($mP["c"]);
                }
                $mWhereClause[] = $mP["c_c"] . "='" . $mP["c"] . "'";
            }

            if (isset($_SESSION["usr_id"])) {
                $mUserStatement = " AND (jt.gc_usr_id=" . $_SESSION["usr_id"] . ") ";
            }

            if ($includeWhereClause) {
                $mWhereClause = " WHERE " . implode($mWhereClause,
                                " AND ");
            } else {
                $mWhereClause = "";
            }

            if ($includeOrderByClause) {
                $mOrderByClause = " ORDER BY gc_name ASC, " . $mP["nc"] . " ASC ";
            }

            /*
             * Determine the name of the table to join to the datasource based on the
             * datasource id and the naming rules
             */
            $mJoinTableName = "ds" . $mP["dsID"] . "_match";

            /*
             * Construct select statement
             */
            $mSql = sprintf("SELECT COALESCE(gc_name, %s) as _nc, %s as _x, %s as _y, ds.%s as _id, %s as _ic, %s as _uc, ds.*, jt.* FROM %s ds LEFT JOIN %s jt ON jt.fk_ds_id = ds.%s %s %s %s LIMIT %s OFFSET %s;",
                    $mP["nc"],
                    $mP["xc"],
                    $mP["yc"],
                    $mP["idc"],
                    $mP["ic"],
                    $mP["uc"],
                    $mP["t"],
                    $mJoinTableName,
                    $mP["idc"],
                    $mUserStatement,
                    $mWhereClause,
                    $mOrderByClause,
                    $mP["limit"],
                    $mP["offset"]
            );
            
            logIt($mSql);

            $result = Db::query($mSql);
            if ($result) {
                $this->_result->setSuccess(ErrorMsgs::success);
                while (null !== ($obj = $result->fetch_object())) {
                    $obj->gc_alternates = array();
                    $this->_result->addData($obj,
                            (string) $obj->_id);
                }

                if (count($this->_result->records) > 0) {
                    /*
                     * The main loop only takes coordinates for the current user and
                     * he/she may not have geocoding for the item. In order to load
                     * existing geocoding added by other users, do a loop through the batch
                     * of search results and select any existing geocodes.
                     * 
                     * NB! This function costs an extra and potentially costly query and it
                     * is therefore necessary to assess alternative methods in the long run
                     */
                    $mKeys = array_keys($this->_result->records);
                    if (is_string($mKeys[0])) {
                        $mKeys = "'" . implode($mKeys,
                                        "','") . "'";
                    } else {
                        $mKeys = implode($mKeys,
                                ",");
                    }
                    $isql = "SELECT fk_ds_id, gc_lat, gc_lon, gc_confidence FROM " . $mJoinTableName . " WHERE gc_usr_id != " . $_SESSION["usr_id"] . " AND fk_ds_id in (" . $mKeys . ") ORDER BY gc_confidence ASC";
                    $iresult = Db::query($isql);
                    if ($iresult) {
                        while ($obj2 = $iresult->fetch_object()) {
                            $this->_result->records[(string) $obj2->fk_ds_id]->gc_alternates[] = $obj2;

                            /*
                             * If the current user does not have geocoding for the item
                             * take the coordinates of the best rated existing geocoding
                             * if any
                             */
                            if (!is_numeric($this->_result->records[(string) $obj2->fk_ds_id]->gc_lon) ||
                                    !is_numeric($this->_result->records[(string) $obj2->fk_ds_id]->gc_lat)) {
                                $this->_result->records[(string) $obj2->fk_ds_id]->gc_lon = $obj2->gc_lon;
                                $this->_result->records[(string) $obj2->fk_ds_id]->gc_lat = $obj2->gc_lat;
                                $this->_result->records[(string) $obj2->fk_ds_id]->gc_confidence = $obj2->gc_confidence;
                            }
                        }
                        $iresult->close();
                    } else {
                        /*
                         * If something went wrong during the SQL query for additional
                         * geocoded points from other users
                         */
                        $this->_result->setFailure(ErrorMsgs::sqlError . mysqli_error(Db::$conn),
                                ErrorMsgs::sqlStatement . $isql);
                    }
                } else {
                    $this->_result->setSuccess(ErrorMsgs::noResults);
                }
            } else {
                /*
                 * If there was an error loading the first set of items
                 */
                $this->_result->setFailure(ErrorMsgs::failure,
                        ErrorMsgs::sqlError . mysqli_error(Db::$conn));
                $this->_result->addMsg(ErrorMsgs::sqlStatement . $mSql);
            }
        }

        $this->_result->echoJson();
    }

    public static function getInstance() {
        return new WsLoadSourceItems();
    }

}

WsLoadSourceItems::getInstance()->run(true);
