<?php

include_once 'config.php';
include_once 'filter_inputs.php';

$options["--stylesheet"] = "$BASEDIR/$TEMPLATESDIR/${options["--stylesheet"]}.xsl#";
if (isset($options["name"])) {
    $options["--stylesheet"] .= "name=" . rawurlencode($options["name"]) . "&";
}
if (isset($options["original"])) {
    $options["--stylesheet"] .= "original=" . rawurlencode($options["original"]) . "&";
}
if (isset($options["refreshPeriod"])) {
    $options["--stylesheet"] .= "refreshPeriod={$options["refreshPeriod"]}&";
}

$args = "";
foreach ($options as $option => $value) {
    if (substr($option, 0, 1) == '-') {
        if (is_null($value)) {
            http_response_code(400);
            $errorMessage = "Valeur incorrecte pour le paramètre <var>$option</var> : " . filter_input(INPUT_GET, $option, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            include_once ".";
            die();
        } else if ($value) {
            if ($value === true) {
                $args .= " $option";
            } else {
                if (substr($option, 0, 2) == '--') $args .= " $option " . escapeshellarg($value);
                else $args .= " $option" . escapeshellarg($value);
            }
        }
    }
}


$command = "$NMAP $args -oX - $targets";

if (isset($options["name"])) {
    if (!file_exists($SCANSDIR)) mkdir($SCANSDIR);
    $path = "$SCANSDIR/{$options["name"]}.xml";
    $command .= " | tee '$path'";
}

header('Content-type: text/xml');
system($command, $retcode);

exit();
