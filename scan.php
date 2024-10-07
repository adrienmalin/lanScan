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
$initPath = "$SCANS_DIR/".str_replace('/', '!', $targets).'_init.xml';
if (file_exists($initPath)) {
    $currentPath = ("$SCANS_DIR/".str_replace('/', '!', $targets).'_current.xml');
} else {
    $currentPath = $initPath;
    $initPath = '';
}

$stylesheetUrl = "$basedir/stylesheet.xsl";

$command = "NMAPDIR=./nmap nmap $NMAP_OPTIONS -oX ".escapeshellarg($currentPath)." --stylesheet $basedir/stylesheet.xsl $targets";

exec($command, $output, $retval);

if (!file_exists($currentPath)) {
    http_response_code(500);
    exit(implode("<br/>\n", $output));
}

// Add params
$xml = new DOMDocument();
$xml->load($currentPath);
$processingInstruction = $xml->createProcessingInstruction('xslt-param', "name='targets' value='$targets'");
$xml->insertBefore($processingInstruction, $xml->documentElement);
$processingInstruction = $xml->createProcessingInstruction('xslt-param', "name='basedir' value='$basedir'");
$xml->insertBefore($processingInstruction, $xml->documentElement);
$processingInstruction = $xml->createProcessingInstruction('xslt-param', "name='compareWith' value='$initPath'");
$xml->insertBefore($processingInstruction, $xml->documentElement);
$xml->save($currentPath);

//header('Location: '.$currentPath);
header('Content-type: text/xml');
exit($xml->saveXML());
