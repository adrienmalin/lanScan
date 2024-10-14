<?php

$BASEDIR = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}" . dirname($_SERVER['REQUEST_URI']);

$LANSCAN_OPTIONS = [
    'PS'         => 'ssh,http,https,msrpc,microsoft-ds',
    'F'          => true,
    'T5'         => true,
    'stylesheet' => "$BASEDIR/lanScan.xsl"
];

$HOSTSCAN_OPTIONS = [
    'Pn'         => true,
    'F'          => true,
    'sV'         => true,
    'stylesheet' => "$BASEDIR/hostScan.xsl"
];

$SCANSDIR = 'scans';
$DATADIR  = '/usr/share/nmap';

$sudo = true;