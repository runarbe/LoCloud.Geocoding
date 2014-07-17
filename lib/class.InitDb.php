<?php

/**
 * Description of class
 *
 * @author runarbe
 */
class InitDb {

    public static function readSqlFile($pFileName) {
        $mFileArray = file(dirname(__FILE__) . "/../setup/sql/" . $pFileName . ".sql");
        $mSql = "";
        foreach ($mFileArray as $mFileLine) {
            if (startsWith($mFileLine,
                            '--')) {
                // ignore
            //} else if (startsWith($mFileLine,
              //              "DELIMITER")) {
                // ignore
            } else if (endsWith(trim($mFileLine),
                            '$$')) {
                $mSql .= str_replace('$$',
                        '//',
                        $mFileLine);
            } else {
                $mSql .= $mFileLine;
            }
        }
        return $mSql;
    }

    public static function CreateTables2() {

        // Create the database as per values in config.php
        dbcreate();

        $mSql = InitDb::readSqlFile("schema-full");
        //logIt($mSql);
        return Db::query_multi($mSql);
    }

    /**
     * Create the tables necessary for running the site
     */
    public static function CreateTables() {

        // Create the database as per values in config.php
        dbcreate();

        $mDb = db();

        $mSql1 = file_get_contents(dirname(__FILE__) . "/../setup/sql/schema.sql");
        $mStatus1 = dbqmulti($mDb,
                $mSql1,
                "Create/upgrade database schema");

        $mSql4 = file_get_contents(dirname(__FILE__) . "/../setup/sql/schema-data.sql");
        $mStatus4 = dbqmulti($mDb,
                $mSql4,
                "Populate schema data");

        $mSql2 = file_get_contents(dirname(__FILE__) . "/../setup/sql/demo-source.sql");
        $mStatus2 = dbqmulti($mDb,
                $mSql2,
                "Insert demo source dataset");

        $mSql3 = file_get_contents(dirname(__FILE__) . "/../setup/sql/demo-searchdb.sql");
        $mStatus3 = dbqmulti($mDb,
                $mSql3,
                "Insert demo search database");

        dbc($mDb);
        if ($mStatus1 === true && $mStatus2 === true && $mStatus3 === true && $mStatus4 === true) {
            return true;
        } else {
            return false;
        }
    }

}
