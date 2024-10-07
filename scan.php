<?php

include_once 'config.php';

$targets = filter_input(INPUT_GET, 'targets', FILTER_VALIDATE_REGEXP, [
    'flags' => FILTER_NULL_ON_FAILURE,
    'options' => ['regexp' => "/^[\da-zA-Z.:\/_ -]+$/"],
]);
if (!$targets) {
    http_response_code(400);
    exit('Paramètre targets manquant.');
}

$basedir = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}".dirname($_SERVER['REQUEST_URI']);

$dir = $SCANS_DIR;
if (!file_exists($SCANS_DIR)) {
    mkdir($SCANS_DIR);
}
$firstPath = "$SCANS_DIR/".str_replace('/', '!', $targets).'_init.xml';
if (file_exists($firstPath)) {
    $path = ("$SCANS_DIR/".str_replace('/', '!', $targets).'_current.xml');
} else {
    $path = $firstPath;
    $firstPath = '';
}

$stylesheetUrl = "$basedir/stylesheet.xsl";

$command = "NMAPDIR=./nmap nmap $NMAP_OPTIONS -oX ".escapeshellarg($path)." --stylesheet $basedir/stylesheet.xsl $targets";

exec($command, $output, $retval);

if (!file_exists($path)) {
    http_response_code(500);
    exit(implode("<br/>\n", $output));
}

// Add params
$xml = new DOMDocument();
$xml->load($path);
$processingInstruction = $xml->createProcessingInstruction('xslt-param', "name='targets' value='$targets'");
$xml->insertBefore($processingInstruction, $xml->documentElement);
$processingInstruction = $xml->createProcessingInstruction('xslt-param', "name='basedir' value='$basedir'");
$xml->insertBefore($processingInstruction, $xml->documentElement);
$processingInstruction = $xml->createProcessingInstruction('xslt-param', "name='compareWith' value='$firstPath'");
$xml->insertBefore($processingInstruction, $xml->documentElement);
$xml->save($path);

header('Location: '.$path);
