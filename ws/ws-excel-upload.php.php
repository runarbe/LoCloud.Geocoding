<?php

// Pull gallery config
require_once("../functions.php");

// Pull in the NuSOAP code
require_once("../lib/nusoap/nusoap.php");

// Create the server instance
$server = new soap_server;

// Initialize WSDL support
$server->configureWSDL('gallerywsdl', 'urn:gallerywsdl');

$server->wsdl->addComplexType(
        'stringArray', 'complexType', 'struct', 'all', '', array()
);

$server->wsdl->addComplexType(
        'StrArray', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'xsd:string[]')
        ), 'xsd:string'
);

function renameAlbum($pAlbumName, $pTitle) {
    global $mib;
    /* @var $mib mib */
    return $mib->renameAlbum($pAlbumName, $pTitle);
}

// Register with SOAP
$server->register('renameAlbum', array(
    'pAlbumname' => 'xsd:string',
    'pTitle' => 'xsd:string',
        ), array('return' => 'xsd:string'), 'urn:gallerywsdl', 'urn:gallerywsdl#uploadFile', 'rpc', 'encoded', 'Alters the name of an album'
);

/**
 * Finally, pass all raw http data to the web service and try to execute it
 */
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>