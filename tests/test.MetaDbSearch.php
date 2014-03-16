<?php
require_once("../functions.php");
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$mDBS = new MetaDbSearch();

$mDBS->sch_table = "test";
$mDBS->sch_table = "test2";

echo $mDBS->Insert();
?>
