<?php

$targets = filter_input(INPUT_GET, 'targets', FILTER_VALIDATE_REGEXP, [
  'flags' => FILTER_NULL_ON_FAILURE,
  'options' => ['regexp' => "/^[\da-zA-Z-. \/]+$/"],
]);

$name = filter_input(INPUT_GET, 'name', FILTER_VALIDATE_REGEXP, [
  'flags' => FILTER_NULL_ON_FAILURE,
  'options' => ['regexp' => '/^[^<>:"\/|?]+$/'],
]);

$hostsListRegex = "/^[\da-zA-Z-.,:\/]+$/";
$protocolePortsListRegex = "/^(([TU]:)?[0-9\-]+|[a-z\-]+)(,([TU]:)?[0-9\-]+|,[a-z\-]+)*$/";
$portsListRegex = "/^([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*$/";
$tempoRegex = "/^\d+[smh]?$/";

$input_args = filter_input_array(INPUT_GET, [
  '-iR' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE],
  '--exclude' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $hostsListRegex]],

  '-sL' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $hostsListRegex]],
  '-sP' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-P0' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-PN' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-PS' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $portsListRegex]],
  '-PA' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $portsListRegex]],
  '-PU' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $portsListRegex]],
  '-PE' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-PP' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-PM' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-PO' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['min_range' => 0, 'max_range' => 255]],
  '-n' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-R' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--dns-servers' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $hostsListRegex]],

  '-sS' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-sT' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-sA' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-sW' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-sM' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-sF' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-sN' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-sX' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-PU' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-PM' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-PM' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-PM' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--scanflags' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => "/^([URG|ACK|PSH|RST|SYN|FIN]+)$|^([0-2]?\d?\d)$/"]],
  '-sI' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => "/^[a-zA-Z\d:.-]+(:\d+)?$/"]],
  '-sO' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-b' => FILTER_VALIDATE_DOMAIN,
  '--traceroute' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--reason' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],

  '-p' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $portsListRegex]],
  '-F' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-r' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--top-ports' => FILTER_VALIDATE_INT,
  '--port-ratio' => ['filter' => FILTER_VALIDATE_FLOAT, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['min_range' => 0, 'max_range' => 1]],

  '-sV' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--version-light' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--version-intensity' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['min_range' => 0, 'max_range' => 9]],
  '--version-all' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--version-trace' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],

  '-O' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--osscan-limit' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--osscan-guess' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],

  '-T0' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-T1' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-T2' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-T3' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-T4' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-T5' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--min-hostgroup' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE],
  '--max-hostgroup' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE],
  '--min-parallelism' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE],
  '--max-parallelism' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE],
  '--min-rtt-timeout' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $tempoRegex]],
  '--max-rtt-timeout' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $tempoRegex]],
  '--initial-rtt-timeout' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $tempoRegex]],
  '--max-retries' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE],
  '--host-timeout' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $tempoRegex]],
  '--scan-delay' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $tempoRegex]],
  '--max-scan-delay' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $tempoRegex]],

  '-f' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE],
  '-mtu' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE],
  '-D' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => $hostsListRegex]],
  '-S' => ['filter' => FILTER_VALIDATE_IP, 'flags' => FILTER_NULL_ON_FAILURE],
  '-e' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => "/^[a-z\d]+$/"]],
  '-g' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE],
  '--source-port' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE],
  '--data-length' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE],
  '--ip-options' => ['filter' => FILTER_VALIDATE_REGEXP, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['regexp' => "/^\"(R|T|U|L [\da-zA-Z-.: ]+|S [\da-zA-Z-.: ]+|\\\\x[\da-fA-F]{1,2}(\*[\d]+)?|\\\\[0-2]?[\d]{1,2}(\*[\d]+)?)\"$/"]],
  '-ttl' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_NULL_ON_FAILURE,  'options' => ['min_range' => 0, 'max_range' => 255]],
  '--spoof-mac' => ['filter' => FILTER_VALIDATE_MAC, 'flags' => FILTER_NULL_ON_FAILURE],
  '--badsum' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],

  //'-6' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-A' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--send-eth' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--send-ip' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--privileged' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-V' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '--unprivileged' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
  '-h' => ['filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE],
], false) ?: $DEFAULT_ARGS;
