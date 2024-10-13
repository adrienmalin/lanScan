<?php

include_once 'config.php';
include_once 'filter_inputs.php';

if (!$targets) {
    http_response_code(400);
    die('Paramètre manquant : targets');
}

if (!file_exists($SCANS_DIR)) {
    mkdir($SCANS_DIR);
}

$args = '';
foreach ($inputs as $arg => $value) {
    if (is_null($value)) {
        http_response_code(400);
        die("Valeur incorecte pour le paramètre $arg : " . filter_input(INPUT_GET, $arg, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    } else if ($value) {
        if ($value === true) {
            if (strlen($arg)<=2) $args .= " -$arg";
            else $arg = "--$arg";
        } else {
            if (strlen($arg)<=2) $args .= " -$arg" . ($value);
            else $arg = "--$arg " . ($value);
        }
    }
}

$basedir = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}" . dirname($_SERVER['REQUEST_URI']);

$tempPath = tempnam(sys_get_temp_dir(), 'scan_').".xml";
exec("nmap$args --stylesheet $basedir/stylesheet.xsl -oX '$tempPath' $targets 2>&1", $stderr, $code);
if ($code) {
    http_response_code(500);
    die(implode("<br/>\n", $stderr));
}

$xml = new DOMDocument();
$xml->load($tempPath);
`rm "$tempPath"`;

$xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='saveAs' value='".htmlentities($saveAs, ENT_QUOTES)."'"), $xml->documentElement);
$xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='scansDir' value='".htmlentities($SCANS_DIR, ENT_QUOTES)."'"), $xml->documentElement);
$xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='compareWith' value='".htmlentities($compareWith, ENT_QUOTES)."'"), $xml->documentElement);

if ($saveAs) {
    $path = "$SCANS_DIR/$saveAs.xml";
    $xml->save($path);

    header("Location: $path");
    exit();
} else {
    header('Content-type: text/xml');
    exit($xml->saveXML());
}
