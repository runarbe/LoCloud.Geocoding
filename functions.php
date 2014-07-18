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
    "/lib/class.iRetObj.php",
    "/lib/class.iWebService.php",
    "/lib/class.GcWebService.php",
    "/lib/class.Db.php",
    "/lib/class.InitDb.php",
    "/lib/class.AccessLevels.php",
    "/lib/class.CharacterEncoding.php",
    "/lib/class.W2UIRetObj.php",
    "/lib/class.MySQLTable.php",
    "/lib/class.MetaUploads.php",
    "/lib/class.MetaDatasources.php",
    "/lib/class.MetaDbSearch.php",
    "/lib/class.MetaUsr.php",
    "/lib/class.MetaGroup.php",
    "/lib/class.MsgElevationRequired.php",
    "/lib/class.MsgSessionExpired.php",
    "/lib/class.WsDataTypes.php",
    "/lib/class.ErrorMsgs.php",
    "/lib/class.SearchMatch.php",
    "/lib/class.ParamOpt.php",
    "/lib/class.WsRetObj.php",
    "/lib/class.WsStatus.php",
    "/lib/class.UserLevels.php",
    "/lib/class.MetaAcl.php"
);

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
function inp($pKey,
        $pDataType = null) {
    $mVal = getGetPost($pKey);
    if ($mVal !== null) {
        /*
         * If data type is supplied, check data types
         */
        if ($pDataType === WsDataTypes::mNull) {
            return $mVal;
        } else {
            switch ($pDataType) {
                case WsDataTypes::mString:
                    return $mVal;
                case WsDataTypes::mInteger:
                    return getGetPost($pKey,
                            FILTER_VALIDATE_INT);
                case WsDataTypes::mDouble:
                    return getGetPost($pKey,
                            FILTER_VALIDATE_FLOAT);
                case WsDataTypes::mArray:
                    if (!is_array($mVal)) {
                        return false;
                    }
                    foreach ($mVal as $mInt) {
                        if (!is_numeric($mInt)) {
                            return false;
                        }
                    }
                    return $mVal;
                default:
                    return $mVal;
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
function checkWSParam($pKey,
        &$pRetObj,
        $pDataType = null,
        $pMandatory = true,
        $pDefaultValue = null) {

    /**
     * If no value exists for the key - or if wrong datatype
     */
    $mVal = inp($pKey,
            $pDataType);

    if ($mVal === false) {
        /**
         * If if mandatory or not
         */
        if ($pMandatory === true) {
            /**
             * If mandatory, add error
             */
            $pRetObj->setFailure(ErrorMsgs::reqParamMissing,
                    $pKey);
        } else {

            /**
             * Add message and status
             */
            $pRetObj->addMsg(ErrorMsgs::optParamMissing,
                    $pKey);
            /**
             * If non mandatory check if a default value is specified
             */
            if ($pDefaultValue !== null) {
                $pRetObj->setSuccess("Using default value for parameter",
                        $pKey);
                $mVal = $pDefaultValue;
            }
        }
        $mVal = $pDefaultValue;
    } else {
        $pRetObj->setSuccess();
    }
    /**
     * Return the value
     */
    return $mVal;
}

/**
 * Check if parameters are present in request and return error messages
 * @param Array[wsParam] $pKeyRequiredArray Associative array where the key is the name of the parameter and the value is 0 for optional and 1 for mandatory (by reference)
 * @param WsRetObj $pRetObj The present <wsRetObj> instance (by reference)
 * @return WsStatus
 */
function checkWSParameters(&$pKeyRequiredArray,
        &$pRetObj) {
    if (!is_array($pKeyRequiredArray)) {
        $pRetObj->setFailure("WebService parameter array check error.");
        return $pRetObj->v;
    }
    foreach ($pKeyRequiredArray as $mKey => $mParamOptions) {
        /* @var $mParamOptions ParamOpt */
        $pKeyRequiredArray[$mKey] = checkWSParam($mKey,
                $pRetObj,
                $mParamOptions->mDataType,
                $mParamOptions->mMandatory,
                $mParamOptions->mDefaultValue);
    }
    $pRetObj->v = $pRetObj->v ? $pRetObj->v : WsStatus::success;
    return $pRetObj->v;
}

/**
 * If string is empty, replace with NULL
 * @param string $pString
 * @return string|null
 */
function nullIfEmpty($pString) {
    return trim($pString) == "" ? "NULL" : $pString;
}

/**
 * Create the database
 */
function dbcreate() {
    $mDb = db(true);
    if (!$mDb) {
        logIt(ErrorMsgs::sqlError);
    } else {
        dbq($mDb,
                sprintf("CREATE DATABASE IF NOT EXISTS %s CHARACTER SET utf8 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT COLLATE utf8_general_ci;",
                        LgmsConfig::db));
    }
}

/**
 * Connect to MySQL database and return a connection object
 * 
 * @return mysqli
 */
function db($pConnectWithoutDb = false) {
    if ($pConnectWithoutDb === true) {
        $db = new mysqli(LgmsConfig::db_host,
                LgmsConfig::db_usr,
                LgmsConfig::db_pwd);
    } else {
        $db = new mysqli(LgmsConfig::db_host,
                LgmsConfig::db_usr,
                LgmsConfig::db_pwd,
                LgmsConfig::db);
    }
    $db->set_charset("utf8");
    return $db;
}

/**
 * Get the GET or POST version of a variable
 * @param string $pKey
 * @param int $pFilter
 */
function getGetPost($pKey,
        $pFilter = FILTER_DEFAULT) {

    // First check the GET vars
    $mReturn = filter_input(INPUT_GET,
            $pKey,
            $pFilter);

    // If the value is not set, proceed to check POST vars
    if (!$mReturn) {
        $mReturn = filter_input(INPUT_POST,
                $pKey,
                $pFilter);
    }

    if (!$mReturn) {
        $mReturn = filter_input(INPUT_POST,
                $pKey,
                $pFilter,
                FILTER_REQUIRE_ARRAY);
    }
    return $mReturn;
}

/**
 * Test database connection
 * @param string $pDbHost
 * @param string $pDbUsr
 * @param string $pDbPwd
 * @return boolean
 */
function testDb($pDbHost,
        $pDbUsr,
        $pDbPwd) {
    $db = new mysqli($pDbHost,
            $pDbUsr,
            $pDbPwd);
    if ($db) {
        dbc($db);
        return true;
    } else {
        return false;
    }
}

function checkIfDbExists() {
    $mDb = db(true);
    $mRes = dbq($mDb,
            "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . LgmsConfig::db . "'");
    if (mysqli_num_rows($mRes) == "1") {
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
function dbqmulti($pDb,
        $pQuery,
        $pLabel = "") {

    $mRetVal = true;
    if (mysqli_multi_query($pDb,
                    $pQuery)) {
        do {
            $result = $pDb->use_result();
            if ($pDb->errno === 0) {
                logIt($pDb->affected_rows . ' row(s) affected');
            } else {
                die($pDb->error);
            }
        } while ($pDb->next_result());

        if ($pDb->errno !== 0) {
            logIt(mysqli_error($pDb) + " (" . $pLabel . ")");
            $mRetVal = false;
        }
    } else {
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
function dbq($pDb,
        $pQuery) {
    $mRes = mysqli_query($pDb,
            $pQuery);
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
    return (strpos($pString,
                    "'") >= 0 && strpos($pString,
                    "\'") !== false) ? true : false;
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
function logIt($pObject) {

    $callers = debug_backtrace();
    $mFile0 = $callers[0]['file'];
    $mFunc0 = $callers[0]['function'];
    $mLine0 = $callers[0]['line'];
    $mFile = $callers[1]['file'];
    $mFunc = $callers[1]['function'];
    $mLine = $callers[1]['line'];

    $mString = "------------------------\r\n";

    $mString .= sprintf("%s\tOn line #%s of file '%s'\r\n",
            date("Y-m-d h:i:s"),
            $mLine,
            $mFile);

    $mString .= sprintf("\t...On line #%s of file '%s'\r\n",
            $mLine0,
            $mFile0);

    $mString .= sprintf("\t...%s\r\n",
            var_export($pObject,
                    true));

    $file = dirname(__FILE__) . "/log/messages.txt";
    if (filesize($file) > 512000) {
        file_put_contents($file,
                $mString,
                LOCK_EX);
    } else {
        file_put_contents($file,
                $mString,
                FILE_APPEND | LOCK_EX);
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
    if (file_exists(dirname(__FILE__) . "/config.php") && checkIfDbExists()) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if user is logged in
 * @param UserLevels $pRequiredUserLevel
 * @return boolean
 */
function isLoggedIn($pRequiredUserLevel = null) {
    session_start();
    if (isset($_SESSION["usr_id"]) && isset($_SESSION["usr_level"])) {
        $mCUsrLevel = $_SESSION["usr_level"];
        if ($pRequiredUserLevel == null) {
            return true;
        } else if ($pRequiredUserLevel >= $mCUsrLevel) {
            return true;
        } else {
            return false;
        }
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
 * @param UserLevels $pLevel The level of user that is required
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
    return pathinfo($pPath,
            PATHINFO_FILENAME);
}

/**
 * Get a human readable version of any PHP variable type
 * 
 * @param Mixed $pVar
 * @return String
 */
function getR($pVar) {
    return var_export($pVar,
            true);
}

/**
 * Reads the contents of a local file into a variable and returns it
 * @param string $pFilename
 * @return string
 */
function fileToString($pFilename) {
    $fh = fopen($pFilename,
            'r');
    if ($fh) {
        $data = fread($fh,
                filesize($pFilename));
        fclose($fh);
        return $data;
    } else {
        return "null";
    }
}

/**
 * Copy the values from an object onto the corresponding values in a class
 * @param object $object Any object
 * @param string $class The name of the class you would like returned
 * @return object Instance of the class named in $class parameter
 */
function object_to_class($object,
        $class,
        $recidcol = null,
        $omitinternals = true) {
    $new = new $class();
    foreach ($object as $key => $value) {
        if (property_exists($new,
                        $key)) {
            if ($omitinternals && !startsWith($key,
                            "_")) {
                $new->$key = $value;
            }
        }
        if ($key === "id" && $recidcol !== null) {
            $new->recid = $value;
        }
    }
    return $new;
}

/**
 * Get the constants from a class and return them as options
 * @param String $pClassName The name of the enumeration class
 * @param Array $pExclude An array of IDs to exclude
 * @return String HTML OPTION elements
 */
function getOptions($pClassName,
        $pExclude = null) {
    $val = "";
    $mReflection = new ReflectionClass($pClassName);
    foreach ($mReflection->getConstants() as $mKey => $mVal) {
        if ($pExclude === null || !array_search($mVal,
                        $pExclude)) {
            $val .= "<option value=\"$mVal\">$mKey</option>";
        }
    }
    return $val;
}

function startsWith($haystack,
        $needle) {
    $length = strlen($needle);
    return (substr($haystack,
                    0,
                    $length) === $needle);
}

function endsWith($haystack,
        $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack,
                    -$length) === $needle);
}

/**
 * Return the user level of the currently logged in user
 * @return UserLevels User level
 */
function cLevel() {
    session_start();
    if (isset($_SESSION["usr_level"])) {
        return $_SESSION["usr_level"];
    } else {
        return UserLevels::GuestUser;
    }
}

/**
 * Return the user level name of the currently logged in user
 * @return UserLevels User level name
 */
function cLevelName() {
    session_start();
    $mReturn = "";
    $mUsrLevel = cLevel();
    $mLevels = new ReflectionClass("UserLevels");
    foreach ($mLevels->getConstants() as $mConstantKey => $mConstantVal) {
        if ($mConstantVal == $mUsrLevel) {
            $mReturn = $mConstantKey;
        }
    }
    return $mReturn;
}

/**
 * Return the user ID of the currently logged in user
 * @return int User ID
 */
function cUsr() {
    session_start();
    if (isset($_SESSION["usr_id"])) {
        return $_SESSION["usr_id"];
    } else {
        return -1;
    }
}

/**
 * Return the username of the currently logged in user
 * @return string User name
 */
function cUsrName() {
    session_start();
    if (isset($_SESSION["usr_usr"])) {
        return $_SESSION["usr_usr"];
    } else {
        return "&lt;not set&gt;";
    }
}
