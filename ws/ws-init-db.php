<?php

require_once("../functions.php");

$mWsRetObj = new WsRetObj();
$mInit = InitDb::CreateTables2();

if ($mInit === false) {
    $mWsRetObj->setFailure("Failed to create database");
} else {
    $mWsRetObj->setSuccess("Succeeded in creating database");
}

$mWsRetObj->echoJson();