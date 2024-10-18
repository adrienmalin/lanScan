<?php

$BASEDIR = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}" . dirname($_SERVER['REQUEST_URI']);
$SCANSDIR = 'scans';
$NMAPDIR  = dirname(`which nmap`) . "/../share/nmap";
$DATADIR = ".";

$presets = [
    "lan" => [
        '-PS'          => 'microsoft-ds',
        '-F'           => true,
        '-T5'          => true,
        '--stylesheet' => "$BASEDIR/xslt/hostsTable.xsl",
        'refreshPeriod' => 60,
        'sudo'          => false,
    ],
    "host" => [
        '-Pn'           => true,
        '-F'            => true,
        '-sV'           => true,
        '-T5'           => true,
        '--datadir'     => "$DATADIR",
        '--stylesheet'  => "$BASEDIR/xslt/servicesTable.xsl",
        'refreshPeriod' => 60,
        'sudo'          => true,
    ],
];