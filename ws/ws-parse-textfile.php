<?php

require('../functions.php');

dieIfSessionExpired();
dieIfInsufficientElevation(UserLevels::Editor);

$r = new WsRetObj();

$mParams = array(
    "fn" => new ParamOpt(true),
    "delimiter" => new ParamOpt(true),
    "fnfirstrow" => new ParamOpt(true),
    "encoding" => new ParamOpt(true)
);

checkWSParameters($mParams, $r);

// Add upload path to filename
$mParams["fn"] = "../upload/" . $mParams["fn"];

if (!file_exists($mParams["fn"])) {
    $r->v = WsStatus::failure;
    $r->addMsg("File doesn't exist", $mParams["fn"]);
}

if ($r->v == WsStatus::failure) {
    echo $r->getResult();
} else {

    // Create new CSV parser
    $mCsvParser = new CsvParser($mParams["fn"], db());

    // Set delimiter from paramters
    $mCsvParser->setDelimiter($mParams["delimiter"]);

    // Set whether first row contains fieldnames
    if ($mParams["fnfirstrow"] === "true") {
        $mCsvParser->firstRowFieldNames = true;
    } else {
        $mCsvParser->firstRowFieldNames = false;
    }

    // Set source encoding - target ususally UTF-8
    $mCsvParser->sourceEncoding = $mParams["encoding"];

    // Parse data
    $mCsvParser->parseData();
    // 
    if (count($mCsvParser->fields) < 2) {
        $r->setFailure("There is less than two fields in your table, have you specified the correct delimiter character?", $mParams["delimiter"]);
        $r->addMsg("The first line of data in the uploaded file looks like this", $mCsvParser->firstLineRaw);
        if (strpos($mCsvParser->firstLineRaw, ";") !== false) {
            $r->addMsg("The file contains semicolon (;)");
        }
        if (strpos($mCsvParser->firstLineRaw, ",") !== false) {
            $r->addMsg("The file contains comma (,)");
        }
        if (strpos($mCsvParser->firstLineRaw, "\t") !== false) {
            $r->addMsg("The file contains tab (\t)");
        }
    } else {

        if (!$mCsvParser->createTable()) {
            $r->setFailure("Could not create table");
        }
        while ($mCsvParser->insertChunk()) {
        };
        
        $r->addData($mCsvParser->fields, "fields");
        $r->addData($mCsvParser->tmpName, "table");
    }

    unlink($mParams["fn"]);
    echo $r->getResult();
}