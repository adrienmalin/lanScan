<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

include_once 'config.php';

$fileNameRegex = '/^[0-9a-zA-Z-_. ]+$/';

$name = filter_input(INPUT_GET, 'name', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $fileNameRegex], "flags" => FILTER_NULL_ON_FAILURE]);
if (!$name) {
  die("Paramètre manquant ou incorrect : name");
}

$path = "$SCANSDIR/$name.xml";
if (!file_exists($path)) {
  die("Scan inconnu : $name");
}

unlink($path);

header('Location: .');