<?php

$BASEDIR = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}" . dirname($_SERVER['REQUEST_URI']);
$SCANSDIR = 'scans';
$DATADIR  = '/usr/share/nmap';

$presets = [
    "lan" => [
        'PS'         => 'microsoft-ds',
        'F'          => true,
        'T5'         => true,
        'stylesheet' => "$BASEDIR/lanScan.xsl"
    ],
    "host" => [
        'Pn'         => true,
        'F'          => true,
        'sV'         => true,
        'T5'         => true,
        'stylesheet' => "$BASEDIR/hostScan.xsl"
    ],
];

$saveAs        = null;
$compareWith   = null;
$refreshPeriod = 60;
$sudo          = true;

