<?php

include_once 'config.php';

$targets = filter_input(INPUT_GET, 'targets', FILTER_VALIDATE_REGEXP, [
    'flags' => FILTER_NULL_ON_FAILURE,
    'options' => ['regexp' => "/^[\da-zA-Z.:\/_ -]+$/"],
]);

$name = filter_input(INPUT_GET, 'name', FILTER_VALIDATE_REGEXP, [
    'flags' => FILTER_NULL_ON_FAILURE,
    'options' => ['regexp' => '/^[^@<>:"\/|!?]+$/'],
]);

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

$basedir = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}".dirname($_SERVER['REQUEST_URI']);

$result = `nmap $NMAP_OPTIONS --stylesheet $basedir/stylesheet.xsl -oX - $targets`;
if (!$result) {
    http_response_code(500);
    exit();
}

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
