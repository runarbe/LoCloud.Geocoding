<?php
require_once("../functions.php");

$mDS = new MetaDatasources();

$mDS->ds_title = "Test";
$mDS->ds_srs = "4326";
$mDS->ds_col_name = "Name";
$mDS->ds_coord_prec = 4;
$mDS->ds_col_pk = "id";

echo $mDS->insert();

$mDS->id = 1;

echo $mDS->update();
echo $mDS->delete();
     

?>
