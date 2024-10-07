<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include_once 'config.php';

$targets = filter_input(INPUT_GET, 'targets', FILTER_VALIDATE_REGEXP, [
    'flags' => FILTER_NULL_ON_FAILURE,
    'options' => ['regexp' => "/^[\da-zA-Z.:\/_ -]+$/"],
]);
if (!$targets) {
    http_response_code(400);
    exit('Paramètre targets manquant.');
}

$dir = $SCANS_DIR;
if (!file_exists($SCANS_DIR)) {
    mkdir($SCANS_DIR);
}

$initPath = "$SCANS_DIR/".str_replace('/', '!', $targets).'_init.xml';
if (file_exists($initPath)) {
    $currentPath = ("$SCANS_DIR/".str_replace('/', '!', $targets).'_current.xml');
} else {
    $currentPath = $initPath;
    $initPath = '';
}

$result = `nmap $NMAP_OPTIONS --stylesheet stylesheet.xsl -oX - $targets`;

if ($result) {
    $xml = new DOMDocument();
    $xml->loadXML($result);
    $xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='targets' value='$targets'"), $xml->documentElement);

    $dir = $SCANS_DIR;
    if (!file_exists($SCANS_DIR)) {
        mkdir($SCANS_DIR);
    }
    
    $path = "$SCANS_DIR/".str_replace('/', '!', $targets).'.xml';
    if (!file_exists($path)) {
        $xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='compareWith' value=''"), $xml->documentElement);
        $xml->save($path);
    } else {
        $xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='compareWith' value='$path'"), $xml->documentElement);
    }

    header('Content-type: text/xml');
    exit($xml->saveXML());
} else {
    http_response_code(500);
    exit();
}