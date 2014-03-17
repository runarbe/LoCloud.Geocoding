<?php
header('Content-Type: text/html; charset=utf-8');

require_once("../../functions.php");

$t = new CsvParser(dirname(__FILE__) . "/test-data3.csv");

$t->chunkSize = 100;

$t->parseData();

echo $t->createTable();


  while ($rows = $t->insertChunk()) {
    echo $rows;
}

$t->dropTable();


?>
