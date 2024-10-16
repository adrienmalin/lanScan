<?php

include_once 'config.php';
include_once 'filter_inputs.php';

if (!$targets) {
    http_response_code(400);
    $errorMessage = 'Paramètre manquant : targets, lan ou host';
    include_once "options.php";
    die();
}

if (!file_exists($SCANSDIR)) mkdir($SCANSDIR);

$args = '';
foreach ($inputs as $arg => $value) {
    if (is_null($value)) {
        http_response_code(400);
        $errorMessage = "Valeur incorecte pour le paramètre $arg : " . filter_input(INPUT_GET, $arg, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        include_once "options.php";
        die();
    } else if ($value) {
        if ($value === true) {
            if (strlen($arg) <= 2) $args .= " -$arg";
            else $args .= " --$arg";
        } else {
            if (strlen($arg) <= 2) $args .= " -$arg$value";
            else $args .= " --$arg $value";
        }
    }
}

$tempPath = tempnam(sys_get_temp_dir(), 'scan_').".xml";

$command = ($use_sudo? "sudo " : "") . "nmap$args -oX '$tempPath' $targets 2>&1";

exec($command, $stderr, $retcode);

if ($retcode) {
    http_response_code(500);
    $errorMessage = implode("<br/>\n", $stderr);
    include_once "options.php";
    die();
}

$xml = new DOMDocument();
$xml->load($tempPath);
`rm "$tempPath"`;

$saveAsURL = $saveAs? "$BASEDIR/$SCANSDIR/$saveAs.xml" : "";
$xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='savedAs' value='".htmlentities($saveAsURL, ENT_QUOTES)."'"), $xml->documentElement);
$xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='compareWith' value='".htmlentities($compareWith, ENT_QUOTES)."'"), $xml->documentElement);
$xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='refreshPeriod' value='".htmlentities($refreshPeriod, ENT_QUOTES)."'"), $xml->documentElement);

if ($saveAs) {
    $path = "$SCANSDIR/$saveAs.xml";
    $xml->save($path);

    header("Location: $path");
    exit();
} else {
    header('Content-type: text/xml');
    exit($xml->saveXML());
}
