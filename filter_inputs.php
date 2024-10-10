<?php

$targets = filter_input(INPUT_GET, 'targets', FILTER_VALIDATE_REGEXP, [
  'flags'   => FILTER_NULL_ON_FAILURE,
  'options' => ['regexp' => "/^[\da-zA-Z-. \/]+$/"],
]);

$name = filter_input(INPUT_GET, 'name', FILTER_VALIDATE_REGEXP, [
  'flags'   => FILTER_NULL_ON_FAILURE,
  'options' => ['regexp' => '/^[^<>:"\/|?]+$/'],
]);

$hostsListRegex = "/^[\da-zA-Z-.,:\/]+$/";
$protocolePortsListRegex = "/^(([TU]:)?[0-9\-]+|[a-z\-]+)(,([TU]:)?[0-9\-]+|,[a-z\-]+)*$/";
$portsListRegex = "/^([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*$/";
$tempoRegex = "/^\d+[smh]?$/";

$inputs = filter_input_array(INPUT_GET, [
  'iR'       => ['filter' => FILTER_VALIDATE_INT],
  '-exclude' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $hostsListRegex]],

  'sL'           => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $hostsListRegex]],
  'sP'           => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'P0'           => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'Pn'           => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'PS'           => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $portsListRegex]],
  'PA'           => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $portsListRegex]],
  'PU'           => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $portsListRegex]],
  'PE'           => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'PP'           => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'PM'           => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'PO'           => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 0, 'max_range' => 255]],
  'n'            => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'R'            => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-dns-servers' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $hostsListRegex]],

  'sS'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'sT'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'sA'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'sW'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'sM'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'sF'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'sN'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'sX'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'PU'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'PM'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'PM'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'PM'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-scanflags'  => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => "/^([URG|ACK|PSH|RST|SYN|FIN]+)$|^([0-2]?\d?\d)$/"]],
  'sI'          => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => "/^[a-zA-Z\d:.-]+(:\d+)?$/"]],
  'sO'          => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'b'           => FILTER_VALIDATE_DOMAIN,
  '-traceroute' => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-reason'     => ['filter' => FILTER_VALIDATE_BOOLEAN],

  'p'           => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $portsListRegex]],
  'F'           => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'r'           => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-top-ports'  => FILTER_VALIDATE_INT,
  '-port-ratio' => ['filter' => FILTER_VALIDATE_FLOAT, 'options' => ['min_range' => 0, 'max_range' => 1]],

  'sV'                 => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-version-light'     => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-version-intensity' => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 0, 'max_range' => 9]],
  '-version-all'       => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-version-trace'     => ['filter' => FILTER_VALIDATE_BOOLEAN],

  'O'             => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-osscan-limit' => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-osscan-guess' => ['filter' => FILTER_VALIDATE_BOOLEAN],

  'T0'                   => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'T1'                   => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'T2'                   => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'T3'                   => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'T4'                   => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'T5'                   => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-min-hostgroup'       => ['filter' => FILTER_VALIDATE_INT],
  '-max-hostgroup'       => ['filter' => FILTER_VALIDATE_INT],
  '-min-parallelism'     => ['filter' => FILTER_VALIDATE_INT],
  '-max-parallelism'     => ['filter' => FILTER_VALIDATE_INT],
  '-min-rtt-timeout'     => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $tempoRegex]],
  '-max-rtt-timeout'     => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $tempoRegex]],
  '-initial-rtt-timeout' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $tempoRegex]],
  '-max-retries'         => ['filter' => FILTER_VALIDATE_INT],
  '-host-timeout'        => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $tempoRegex]],
  '-scan-delay'          => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $tempoRegex]],
  '-max-scan-delay'      => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $tempoRegex]],

  'f'            => ['filter' => FILTER_VALIDATE_INT],
  'mtu'          => ['filter' => FILTER_VALIDATE_INT],
  'D'            => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $hostsListRegex]],
  'S'            => ['filter' => FILTER_VALIDATE_IP],
  'e'            => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => "/^[a-z\d]+$/"]],
  'g'            => ['filter' => FILTER_VALIDATE_INT],
  '-source-port' => ['filter' => FILTER_VALIDATE_INT],
  '-data-length' => ['filter' => FILTER_VALIDATE_INT],
  '-ip-options'  => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => "/^\"(R|T|U|L [\da-zA-Z-.: ]+|S [\da-zA-Z-.: ]+|\\\\x[\da-fA-F]{1,2}(\*[\d]+)?|\\\\[0-2]?[\d]{1,2}(\*[\d]+)?)\"$/"]],
  'ttl'          => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 0, 'max_range' => 255]],
  '-spoof-mac'   => ['filter' => FILTER_VALIDATE_MAC],
  '-badsum'      => ['filter' => FILTER_VALIDATE_BOOLEAN],

  //'6' => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'A'             => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-send-eth'     => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-send-ip'      => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-privileged'   => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'V'             => ['filter' => FILTER_VALIDATE_BOOLEAN],
  '-unprivileged' => ['filter' => FILTER_VALIDATE_BOOLEAN],
  'h'             => ['filter' => FILTER_VALIDATE_BOOLEAN],
], false) ?: $DEFAULT_ARGS;
