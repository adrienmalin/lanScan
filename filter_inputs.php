<?php

$targetsListRegex        = "/^[\da-zA-Z-. \/]+$/";
$hostsListRegex          = "/^[\da-zA-Z-.,:\/]+$/";
$protocolePortsListRegex = "/^(([TU]:)?[0-9\-]+|[a-z\-]+)(,([TU]:)?[0-9\-]+|,[a-z\-]+)*$/";
$portsListRegex          = "/^([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*$/";
$tempoRegex              = "/^\d+[smh]?$/";
$fileNameRegex           = '/^[^<>:"\/|?]+$/';

$targets = filter_input(INPUT_GET, 'targets', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $targetsListRegex]]);
$lan = filter_input(INPUT_GET, 'lan', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $targetsListRegex]]);
$host = filter_input(INPUT_GET, 'host', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $targetsListRegex]]);
$saveAs = filter_input(INPUT_GET, 'saveAs', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $fileNameRegex]]);
$compareWith = filter_input(INPUT_GET, 'compareWith', FILTER_VALIDATE_URL);


if ($lan) {
  $targets = $lan;
  $inputs = $LANSCAN_OPTIONS;
} else if ($host) {
  $targets = $host;
  $inputs = $HOSTSCAN_OPTIONS;
} else {
  $inputs = filter_input_array(INPUT_GET, [
    'iR'      => FILTER_VALIDATE_INT,
    'exclude' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $hostsListRegex]],

    'sL'          => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $hostsListRegex]],
    'sP'          => FILTER_VALIDATE_BOOLEAN,
    'P0'          => FILTER_VALIDATE_BOOLEAN,
    'Pn'          => FILTER_VALIDATE_BOOLEAN,
    'PS'          => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $portsListRegex]],
    'PA'          => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $portsListRegex]],
    'PU'          => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $portsListRegex]],
    'PE'          => FILTER_VALIDATE_BOOLEAN,
    'PP'          => FILTER_VALIDATE_BOOLEAN,
    'PM'          => FILTER_VALIDATE_BOOLEAN,
    'PO'          => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 0, 'max_range' => 255]],
    'PR'          => FILTER_VALIDATE_BOOLEAN,
    'send-ip'     => FILTER_VALIDATE_BOOLEAN,
    'n'           => FILTER_VALIDATE_BOOLEAN,
    'R'           => FILTER_VALIDATE_BOOLEAN,
    'dns-servers' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $hostsListRegex]],

    'sS'         => FILTER_VALIDATE_BOOLEAN,
    'sT'         => FILTER_VALIDATE_BOOLEAN,
    'sA'         => FILTER_VALIDATE_BOOLEAN,
    'sW'         => FILTER_VALIDATE_BOOLEAN,
    'sM'         => FILTER_VALIDATE_BOOLEAN,
    'sF'         => FILTER_VALIDATE_BOOLEAN,
    'sN'         => FILTER_VALIDATE_BOOLEAN,
    'sX'         => FILTER_VALIDATE_BOOLEAN,
    'scanflags'  => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => "/^([URG|ACK|PSH|RST|SYN|FIN]+)$|^([0-2]?\d?\d)$/"]],
    'sI'         => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => "/^[a-zA-Z\d:.-]+(:\d+)?$/"]],
    'sO'         => FILTER_VALIDATE_BOOLEAN,
    'b'          => FILTER_VALIDATE_URL,
    'traceroute' => FILTER_VALIDATE_BOOLEAN,
    'reason'     => FILTER_VALIDATE_BOOLEAN,

    'p'          => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $portsListRegex]],
    'F'          => FILTER_VALIDATE_BOOLEAN,
    'r'          => FILTER_VALIDATE_BOOLEAN,
    'top-ports'  => FILTER_VALIDATE_INT,
    'port-ratio' => ['filter' => FILTER_VALIDATE_FLOAT, 'options' => ['min_range' => 0, 'max_range' => 1]],

    'sV'                => FILTER_VALIDATE_BOOLEAN,
    'version-light'     => FILTER_VALIDATE_BOOLEAN,
    'version-intensity' => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 0, 'max_range' => 9]],
    'version-all'       => FILTER_VALIDATE_BOOLEAN,
    'version-trace'     => FILTER_VALIDATE_BOOLEAN,

    'O'            => FILTER_VALIDATE_BOOLEAN,
    'osscan-limit' => FILTER_VALIDATE_BOOLEAN,
    'osscan-guess' => FILTER_VALIDATE_BOOLEAN,

    'T0'                  => FILTER_VALIDATE_BOOLEAN,
    'T1'                  => FILTER_VALIDATE_BOOLEAN,
    'T2'                  => FILTER_VALIDATE_BOOLEAN,
    'T3'                  => FILTER_VALIDATE_BOOLEAN,
    'T4'                  => FILTER_VALIDATE_BOOLEAN,
    'T5'                  => FILTER_VALIDATE_BOOLEAN,
    'min-hostgroup'       => FILTER_VALIDATE_INT,
    'max-hostgroup'       => FILTER_VALIDATE_INT,
    'min-parallelism'     => FILTER_VALIDATE_INT,
    'max-parallelism'     => FILTER_VALIDATE_INT,
    'min-rtt-timeout'     => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $tempoRegex]],
    'max-rtt-timeout'     => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $tempoRegex]],
    'initial-rtt-timeout' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $tempoRegex]],
    'max-retries'         => FILTER_VALIDATE_INT,
    'host-timeout'        => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $tempoRegex]],
    'scan-delay'          => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $tempoRegex]],
    'max-scan-delay'      => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $tempoRegex]],

    'f'           => FILTER_VALIDATE_INT,
    'mtu'         => FILTER_VALIDATE_INT,
    'D'           => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => $hostsListRegex]],
    'S'           => ['filter' => FILTER_VALIDATE_IP],
    'e'           => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => "/^[a-z\d]+$/"]],
    'g'           => FILTER_VALIDATE_INT,
    'source-port' => FILTER_VALIDATE_INT,
    'data-length' => FILTER_VALIDATE_INT,
    'ip-options'  => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => "/^\"(R|T|U|L [\da-zA-Z-.: ]+|S [\da-zA-Z-.: ]+|\\\\x[\da-fA-F]{1,2}(\*[\d]+)?|\\\\[0-2]?[\d]{1,2}(\*[\d]+)?)\"$/"]],
    'ttl'         => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 0, 'max_range' => 255]],
    'spoof-mac'   => FILTER_VALIDATE_MAC,
    'badsum'      => FILTER_VALIDATE_BOOLEAN,

      //'6' => FILTER_VALIDATE_BOOLEAN,
    'A'            => FILTER_VALIDATE_BOOLEAN,
    'send-eth'     => FILTER_VALIDATE_BOOLEAN,
    'privileged'   => FILTER_VALIDATE_BOOLEAN,
    'V'            => FILTER_VALIDATE_BOOLEAN,
    'unprivileged' => FILTER_VALIDATE_BOOLEAN,
    'h'            => FILTER_VALIDATE_BOOLEAN,
    'stylesheet'   => FILTER_VALIDATE_URL,
  ], false) ?: $LANSCAN_OPTIONS;
}