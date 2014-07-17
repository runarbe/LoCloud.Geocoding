<?php
require_once("../functions.php");
$r = new WsRetObj();
$r->setSuccess();
$r->d = CharacterEncoding::getEncodings();
echo $r->getResult();
