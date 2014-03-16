<?php

$mVersion = file_get_contents(dirname(__FILE__) . "/version.txt");
$mZipFileName = sprintf("locloudgc-%s.zip", $mVersion);
header(sprintf('Content-Disposition: attachment; filename="%s"', $mZipFileName));
?>
