<?php

include_once 'config.php';

$lan = filter_input(INPUT_GET, 'lan', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[\da-zA-Z-. \/]+$/'], "flags" => FILTER_NULL_ON_FAILURE]);
if ($lan) {
  $cmd = "$lanScanCmd $lan";

  if (!file_exists($SCANSDIR)) mkdir($SCANSDIR);
  $filename = str_replace("/", "!", $lan);
  $path = "$SCANSDIR/$filename.xml";

  if (!file_exists($path)) $cmd .= " | tee '$path'";

  header('Content-type: text/xml');
  system("$cmd", $retcode);

  exit();
}

$host = filter_input(INPUT_GET, 'host', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[\da-zA-Z-. \/]+$/'], "flags" => FILTER_NULL_ON_FAILURE]);
if ($host) {
  $cmd = "$hostScanCmd $host";

  if (!file_exists($SCANSDIR)) mkdir($SCANSDIR);
  $filename = str_replace("/", "!", $host);
  $path = "$SCANSDIR/$filename.xml";

  if (!file_exists($path)) $cmd .= " | tee '$path'";

  header('Content-type: text/xml');
  system("$cmd", $retcode);

  exit();
}

include "home.php";