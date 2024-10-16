<?php

include_once 'filter_inputs.php';

if (!file_exists($SCANSDIR)) mkdir($SCANSDIR);

$command = ($options["sudo"]?? false ? "sudo " : "") . "nmap";
foreach ($options as $arg => $value) {
    if (substr($arg, 0, 1) == '-') {
        if (is_null($value)) {
            http_response_code(400);
            $errorMessage = "Valeur incorecte pour le paramètre <var>$arg</var> : " . filter_input(INPUT_GET, $arg, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            include_once "options.php";
            die();
        } else if ($value) {
            if ($value === true) {
                $command .= " $arg";
            } else {
                if (substr($arg, 0, 2) == '--') $command .= " $arg $value";
                else $command .= " $arg$value";
            }
        }
    }
}

$tempPath = tempnam(sys_get_temp_dir(), 'scan_').".xml";

$command .= " -oX '$tempPath' $targets 2>&1";

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

$saveAsURL = isset($options["saveAs"])? "$BASEDIR/$SCANSDIR/{$options["saveAs"]}.xml" : "";
$xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='savedAs' value='".htmlentities($saveAsURL, ENT_QUOTES)."'"), $xml->documentElement);
$xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='compareWith' value='".htmlentities($options["compareWith"] ?? "", ENT_QUOTES)."'"), $xml->documentElement);
$xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='refreshPeriod' value='".htmlentities($options["refreshPeriod"] ?? "", ENT_QUOTES)."'"), $xml->documentElement);

if (isset($options["saveAs"])) {
    $path = "$SCANSDIR/{$options["saveAs"]}.xml";
    $xml->save($path);

    header("Location: $path");
    exit();
} else {
    header('Content-type: text/xml');
    exit($xml->saveXML());
}
