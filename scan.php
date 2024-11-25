<?php

include_once 'config.php';
include_once 'filter_inputs.php';

$options["--datadir"] = $DATADIR;
$options["--script-args-file"] = $SCRIPTARGS;

if (!file_exists($SCANSDIR)) mkdir($SCANSDIR);

if (!$options["name"]) $options["name"] = str_replace('/', '!', $targets);

//$command = ($options["sudo"]?? false ? "sudo " : "") . "nmap";
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

$path = "$SCANSDIR/{$options["name"]}.xml";

$command = "nmap $args -oX - $targets | tee '$path'";

header('Content-type: text/xml');
system($command, $retcode);

exit();
