<?php

include_once 'config.php';

$name = filter_input(INPUT_GET, 'name', FILTER_VALIDATE_REGEXP, [
    'flags' => FILTER_NULL_ON_FAILURE,
    'options' => ['regexp' => '/^[^@<>:"\/|?]+$/'],
]);
if (!$name) {
    http_response_code(400);
    exit('Paramètre name manquant.');
}

$targets = filter_input(INPUT_GET, 'targets', FILTER_VALIDATE_REGEXP, [
    'flags' => FILTER_NULL_ON_FAILURE,
    'options' => ['regexp' => "/^[\da-zA-Z.:\/_ -]+$/"],
]);
if (!$targets) {
    http_response_code(400);
    exit('Paramètre targets manquant.');
}

$basedir = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['REQUEST_URI']);

$dir = $SCANS_DIR;
if (!file_exists($dir)) {
    mkdir($dir);
}
$firstPath = ("$dir/${name}.xml");
if (file_exists($firstPath)) {
    $path = ("$dir/${name}@".date('YmdHis').'.xml');
} else {
    $path = $firstPath;
    $firstPath = '';
}

$stylesheetUrl = "$basedir/stylesheet.xsl";

$command = 'NMAPDIR=./nmap nmap';
$command .= " $NMAP_OPTIONS";
$command .= ' -oX '.escapeshellarg($path);
$command .= ' --stylesheet '.escapeshellarg($stylesheetUrl);
$command .= " $targets";

exec($command, $output, $retval);

if (!file_exists(__DIR__."/$path")) {
    http_response_code(500);
    exit(implode("<br/>\n", $output));
}

// Add params
$xml = new DOMDocument();
$xml->load($path);
$processingInstruction = $xml->createProcessingInstruction('xslt-param', "name='name' value='$name'");
$xml->insertBefore($processingInstruction, $xml->documentElement);
$processingInstruction = $xml->createProcessingInstruction('xslt-param', "name='targets' value='$targets'");
$xml->insertBefore($processingInstruction, $xml->documentElement);
$processingInstruction = $xml->createProcessingInstruction('xslt-param', "name='basedir' value='$basedir'");
$xml->insertBefore($processingInstruction, $xml->documentElement);
$processingInstruction = $xml->createProcessingInstruction('xslt-param', "name='compareWith' value='$basedir/$firstPath'");
$xml->insertBefore($processingInstruction, $xml->documentElement);
$xml->save($path);

header('Location: '.$path);
