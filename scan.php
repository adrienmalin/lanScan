<?php

include_once 'config.php';

$lan = filter_input(INPUT_GET, 'lan', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[\da-zA-Z-. \/]+$/'], "flags" => FILTER_NULL_ON_FAILURE]);
if ($lan) {
  $cmd = $lanScanCmd;
  $targets = $lan;
  $stylesheet = $lanScanStylesheet;
}

if ($cmd) {
  if (!file_exists($SCANSDIR)) mkdir($SCANSDIR);
  $path = "$SCANSDIR/".str_replace("/", "!", $targets).".xml";

  if (file_exists($path)) {
    $cmd .= "?original=".rawurlencode($targets);
    $cmd .= " $targets";
  } else {
    $cmd .= " $targets";
    $command .= " | tee '$path'";
  }

  header('Content-type: text/xml');
  system($cmd, $retcode);
}

exit();