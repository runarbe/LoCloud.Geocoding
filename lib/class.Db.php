<?php

/**
 * Class for interaction with database
 */
class Db {

    /**
     * MySQL connection
     * @var mysqli 
     */
    public static $conn = null;

    /**
     * Execute a query against the database
     * @param type $pSql
     * @return Object|boolean A mysqli result object on success or false on error
     */
    public static function query($pSql) {
        Db::connect();
        $mRes = mysqli_query(Db::$conn,
                $pSql);
        if (mysqli_errno(Db::$conn) === 0) {
            return $mRes;
        } else {
            logIt("In: " . $pSql);
            logIt("..." . mysqli_error(Db::$conn));
            return false;
        }
    }

    /**
     * Execute an SQL statement that returns a single value
     * NB! does not work for boolean values true/false
     * @param String $pSql
     * @return mixed|null
     */
    public static function executeScalar($pSql) {
        Db::connect();
        $mRes = mysqli_query(Db::$conn,
                $pSql);
        if (!is_bool($mRes) && mysqli_errno(Db::$conn) === 0) {
            $mArray = mysqli_fetch_array($mRes);
            return $mArray[0];
        } else {
            logIt("In: " . $pSql);
            logIt("..." . mysqli_error(Db::$conn));
            return null;
        }
    }

    /**
     * Execute an SQL statement discarding the result
     * NB! does not work for boolean values true/false
     * @param String $pSql
     * @return Boolean True on success, false on error
     */
    public static function execute($pSql) {
        Db::connect();
        mysqli_query(Db::$conn,
                $pSql);
        if (mysqli_errno(Db::$conn) === 0) {
            return true;
        } else {
            logIt(mysqli_error(Db::$conn));
            return false;
        }
    }

    /**
     * Count the number of rows in the most recent query, if it should include all records
     * excluding any limit/offset statements, please incldue SQL_CALC_FOUND_ROWS to the field
     * list in the SELECT clause of the SQL statement you'd like the count for
     * @return int|bool The number of records or false on error
     */
    public static function getLastCount() {
        return Db::executeScalar("SELECT FOUND_ROWS();");
    }

    /**
     * Get the last auto id from the previous insert
     * @return int
     */
    public static function lastId() {
        return mysqli_insert_id(Db::$conn);
    }

    /**
     * Connect to the database
     * @return void
     */
    public static function connect() {
        if (Db::$conn === null) {
            Db::$conn = db();
        }
        return;
    }

    /**
     * Escape a string so that it may be used in an SQL statement
     * @param String $pString
     * @return String
     */
    public static function esc($pString) {
        Db::connect();
        return Db::$conn->real_escape_string($pString);
    }

    /**
     * Execute multiple SQL queries
     * @param type $pSql
     * @return boolean
     */
    public static function query_multi($pSql) {
        Db::connect();
        $mRetVal = true;
        if (mysqli_multi_query(Db::$conn,
                        $pSql)) {
            do {
                Db::$conn->use_result();
                if (Db::$conn->errno !== 0) {
                    logIt(Db::$conn->error);
                    $mRetVal = false;
                }
            } while (Db::$conn->more_results() && Db::$conn->next_result() && $mRetVal);

            if (Db::$conn->errno !== 0) {
                logIt(mysqli_error(Db::$conn));
                $mRetVal = false;
            }
        } else {
            logIt(mysqli_error(Db::$conn));
            $mRetVal = false;
        }
        return $mRetVal;
    }

    public static function tableExists($pTableName) {
        $mFlag = Db::executeScalar(sprintf("SELECT COUNT(*)
                                FROM information_schema.tables 
                                WHERE table_schema = '%s' 
                                AND table_name = '%s';",
                                LgmsConfig::db,
                                $pTableName));
        return $mFlag >= 1 ? true : false;
    }

}
