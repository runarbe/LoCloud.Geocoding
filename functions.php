<?php

/**
 * Load configuration file if it exists
 */
if (file_exists(dirname(__FILE__) . "/config.php")) {
    require_once(dirname(__FILE__) . "/config.php");
} else {
    logIt("Configuration file config.php' does not exist...");
}

/**
 * Define array of dependent files
 */
$mDependencies = array(
    "/lib/CsvParser/class.CsvParser.php",
    "/lib/class.CharacterEncoding.php",
    "/lib/class.MySQLTable.php",
    "/lib/class.MetaUploads.php",
    "/lib/class.MetaDatasources.php",
    "/lib/class.MetaDbSearch.php",
    "/lib/class.MsgElevationRequired.php",
    "/lib/class.MsgSessionExpired.php",
    "/lib/class.WsDataTypes.php",
    "/lib/class.WsErrors.php",
    "/lib/class.WsParamOptions.php",
    "/lib/class.WsRetObj.php",
    "/lib/class.WsStatus.php",
    "/lib/class.WsUserLevel.php"
);

class lcgc {
    
}

/**
 * Require files to be included
 */
foreach ($mDependencies as $mDependency) {
    require_once(dirname(__FILE__) . $mDependency);
}

/**
 * Get an input parameter if it exists in either the HttpGET or HttpPOST request header
 * Return false if parameter is not present in either header.
 * @param String $pKey The name of the key to check for
 * @param WsDataTypes $pDataType Any of the constants defined in the class <wsDataTypes>. Default is <wsDataTypes::Null>
 * @return Mixed Value referenced by $pKey if present, <Boolean::false> if not.
 */
function inp($pKey, $pDataType = null) {

    if (isset($_GET[$pKey]) && !empty($_GET[$pKey])) {
        /**
         * Check if parameter is passed as a HttpGET request
         */
        $mTmp = $_GET[$pKey];
    } elseif (isset($_POST[$pKey]) && !empty($_POST[$pKey])) {
        /**
         * Check if parameter is passed as a HttpPOST request
         */
        $mTmp = $_POST[$pKey];
    }

    if (isset($mTmp) && !empty($mTmp)) {
        /*
         * If data type is supplied, check data types
         */
        if ($pDataType == WsDataTypes::mNull) {
            return $mTmp;
        } else {
            switch ($pDataType) {
                case WsDataTypes::mString:
                    return is_string($mTmp) ? $mTmp : false;
                    break;
                case WsDataTypes::mInteger:
                    return is_integer($mTmp) ? $mTmp : false;
                    break;
                case WsDataTypes::mDouble:
                    return is_double($mTmp) ? $mTmp : false;
                    break;
                default:
                    return $mTmp;
                    break;
            }
        }
    } else {
        /**
         * Otherwise, return false
         */
        return false;
    }
}

/**
 * Check if a parameter is present
 * @param String $pKey The key to check for in the HttpGET/HttpPOST header
 * @param WsRetObj $pRetObj The <wsRetObj> object for the web service passed by reference
 * @return Mixed The value referenced by $pKey or <Boolean> false if not present
 */
function checkWSParam($pKey, &$pRetObj, $pDataType = null, $pMandatory = true, $pDefaultValue = null) {

    /**
     * If no value exists for the key - or if wrong datatype
     */
    if (($mTmp = inp($pKey, $pDataType)) === false) {

        /**
         * If if mandatory or not
         */
        if ($pMandatory === true) {
            /**
             * If mandatory, add error
             */
            $pRetObj->setFailure(WsErrors::reqParamMissing, $pKey);
        } else {

            /**
             * Add message and status
             */
            $pRetObj->addMsg(WsErrors::optParamMissing, $pKey);

            /**
             * If non mandatory check if a default value is specified
             */
            if ($pDefaultValue != null) {
                $pRetObj->setSuccess("Using default value for parameter", $pKey);
                $mTmp = $pDefaultValue;
            }
        }
        $mTmp = $pDefaultValue;
    }
    /**
     * Return the value
     */
    return $mTmp;
}

/**
 * Check if parameters are present in request and return error messages
 * @param Array[wsParam] $pKeyRequiredArray Associative array where the key is the name of the parameter and the value is 0 for optional and 1 for mandatory (by reference)
 * @param WsRetObj $pRetObj The present <wsRetObj> instance (by reference)
 */
function checkWsParameters(&$pKeyRequiredArray, &$pRetObj) {
    foreach ($pKeyRequiredArray as $mKey => $mParamOptions) {
        /* @var $mParamOptions WsParamOptions */
        $pKeyRequiredArray[$mKey] = checkWSParam($mKey, $pRetObj, $mParamOptions->mDataType, $mParamOptions->mMandatory, $mParamOptions->mDefaultValue);
    }
}

/**
 * If string is empty, replace with NULL
 * 
 * @param string $pString
 * @return string|null
 */
function nullIfEmpty($pString) {
    return trim($pString) == "" ? "NULL" : $pString;
}

function dbcreate() {
    $mDb = db(true);
    if (!$mDb) {
        logIt(WsErrors::sqlError);
    } else {
        dbq($mDb, sprintf("CREATE DATABASE %s CHARACTER SET utf8 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT COLLATE utf8_general_ci;", LgmsConfig::db));
    }
}

/**
 * Connect to MySQL database and return a connection object
 * 
 * @return mysqli
 */
function db($pConnectWithoutDb = false) {
    if ($pConnectWithoutDb === true) {
        $db = new mysqli(LgmsConfig::db_host, LgmsConfig::db_usr, LgmsConfig::db_pwd);
    } else {
        $db = new mysqli(LgmsConfig::db_host, LgmsConfig::db_usr, LgmsConfig::db_pwd, LgmsConfig::db);
    }
    $db->set_charset("utf8");
    return $db;
}

/**
 * Test database connection
 * @param string $pDbHost
 * @param string $pDbUsr
 * @param string $pDbPwd
 * @return boolean
 */
function testDb($pDbHost, $pDbUsr, $pDbPwd) {
    $db = new mysqli($pDbHost, $pDbUsr, $pDbPwd);
    if ($db) {
        dbc($db);
        return true;
    } else {
        return false;
    }
}

/**
 * Executes one or more SQL statements separated by semi colon, returns the result
 * of the final statement or null on error
 * 
 * @param mysqli $pDb
 * @param string $pQuery
 * @return boolean
 */
function dbqmulti($pDb, $pQuery) {

    $mRetVal = true;
    if (mysqli_multi_query($pDb, $pQuery)) {
        while ($pDb->next_result());
        if (mysqli_errno($pDb)) {
            logIt(mysqli_error($pDb));
            $mRetVal = false;
        }
    } else {
        logIt("Failed");
        $mRetVal = false;
    }
    return $mRetVal;
}

/**
 * Execute a query and return the result on success or null on error
 * @param mysqli $pDb
 * @param string $pQuery
 * @return mixed <mysqli_result> on success, <null> on error
 */
function dbq($pDb, $pQuery) {
    $mRes = mysqli_query($pDb, $pQuery);
    if (mysqli_errno($pDb)) {
        logit(mysqli_error($pDb));
        return null;
    } else {
        return $mRes;
    }
}

/**
 * Check if a variable has unescaped single quotes
 * @param String $pString
 * @return Boolean True of is escaped, false if not escaped
 */
function isEscaped($pString) {
    return (strpos($pString, "'") >= 0 && strpos($pString, "\'") !== false) ? true : false;
}

/**
 * Close database connection
 * @param \mysqli $db Mysql object
 */
function dbc($db) {
    mysqli_close($db);
}

/**
 * Write specified string as a line to logfile
 * @param String $pString The string to be written to the log file.
 * @return Boolean Always returns true
 */
function logIt($pString) {

    $mString = sprintf("%s\t%s\r\n", date("Y-m-d h:i:s"), var_export($pString, true));
    //$file = LgmsConfig::basedir . "/log/messages.txt";
    $file = dirname(__FILE__) . "/log/messages.txt";
    if (filesize($file) > 1024000) {
        file_put_contents($file, $mString, LOCK_EX);
    } else {
        file_put_contents($file, $mString, FILE_APPEND | LOCK_EX);
    }
    return true;
}

/**
 * Get error messages from log file
 * @param Array $pMsgArray Array of strings from ResObj object.
 * @return String
 */
function getMsg($pMsgArray) {
    $r = "<h3>Messages</h3>";
    foreach ($pMsgArray as $key => $val) {
        $r .= "<p>" . htmlentities($val) . "</p>";
    }
    return $r;
}

function checkConfigPHP() {
    if (file_exists(LgmsConfig::basedir . "/config.php")) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if user is logged in
 * @param WsUserLevel $pUserLevel
 * @return boolean
 */
function isLoggedIn($pUserLevel = null) {
    session_start();
    if (isset($_SESSION["usr_id"]) && isset($_SESSION["usr_level"])) {
        return true;
    } else {
        return false;
    }
}

/**
 * If session is expired print message and exit
 */
function dieIfSessionExpired() {
    if (!isLoggedIn()) {
        $msg = new MsgSessionExpired();
        echo $msg->getJson();
        die();
    }
}

/**
 * Demand a certain level of user to execute the requested function
 * @param WsUserLevel $pLevel The level of user that is required
 */
function dieIfInsufficientElevation($pLevel) {
    if ($_SESSION["usr_level"] > $pLevel) {
        $msg = new MsgElevationRequired();
        echo $msg->getJson();
        die();
    }
}

/**
 * Redirect the current Http request to the authentication page
 */
function goAuth() {
    header("Location: ./auth.php?action=login");
}

/**
 * Redirect the current Http request to the firstrun page
 */
function goFirstRun() {
    header("Location: ./firstrun.php");
}

/**
 * 
 * @param type $pPath
 * @return type
 */
function getFn($pPath = __file__) {
    return pathinfo($pPath, PATHINFO_FILENAME);
}

/**
 * Get a human readable version of any PHP variable type
 * 
 * @param Mixed $pVar
 * @return String
 */
function getR($pVar) {
    return var_export($pVar, true);
}

/**
 * Reads the contents of a local file into a variable and returns it
 * @param string $pFilename
 * @return string
 */
function fileToString($pFilename) {
    $fh = fopen($pFilename, 'r');
    if ($fh) {
        $data = fread($fh, filesize($pFilename));
        fclose($fh);
        return $data;
    } else {
        return "null";
    }
}

?>