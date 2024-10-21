<?php

include_once 'config.php';
include_once 'filter_inputs.php';

if (!file_exists($SCANSDIR)) mkdir($SCANSDIR);

$command = ($options["sudo"]?? false ? "sudo " : "") . "nmap";
foreach ($options as $option => $value) {
    if (substr($option, 0, 1) == '-') {
        if (is_null($value)) {
            http_response_code(400);
            $errorMessage = "Valeur incorrecte pour le paramètre <var>$option</var> : " . filter_input(INPUT_GET, $option, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            include_once ".";
            die();
        } else if ($value) {
            if ($value === true) {
                $command .= " $option";
            } else {
                if (substr($option, 0, 2) == '--') $command .= " $option " . escapeshellarg($value);
                else $command .= " $option" . escapeshellarg($value);
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
    include_once ".";
    die();
}

$xml = new DOMDocument();
$xml->load($tempPath);
`rm "$tempPath"`;

$thisURL = $options["saveAs"]?? false ? "$BASEDIR/$SCANSDIR/{$options["saveAs"]}.xml" : "";
$xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='thisURL' value='".htmlentities($thisURL, ENT_QUOTES)."'"), $xml->documentElement);
foreach ($options as $option => $value) {
    if (substr($option, 0, 1) != '-') {
        $xml->insertBefore($xml->createProcessingInstruction('xslt-param', "name='$option' value='".htmlentities($value, ENT_QUOTES)."'"), $xml->documentElement);
    }
}

if ($options["saveAs"] ?? false) {
    $path = "$SCANSDIR/{$options["saveAs"]}.xml";
    $xml->save($path);

    header("Location: $path");
    exit();
} else {
    header('Content-type: text/xml');
    exit($xml->saveXML());
}
