<?php

include_once 'config.php';
include_once 'filter_inputs.php';

if (!$targets) {
    http_response_code(400);
    exit('Paramètre manquant : targets');
}

if (!file_exists($SCANS_DIR)) {
    mkdir($SCANS_DIR);
}

$basedir = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}".dirname($_SERVER['REQUEST_URI']);

$args = str_replace('=', ' ', http_build_query($input_args, '', ' '));

$result = `nmap $args --stylesheet $basedir/stylesheet.xsl -oX - $targets`;
if (!$result) {
    http_response_code(500);
    exit();
}

$xml = new DOMDocument();
$xml->loadXML($result);

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
