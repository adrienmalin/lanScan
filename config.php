<?php

$BASEDIR = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}" . dirname($_SERVER['REQUEST_URI']);
$SCANSDIR = 'scans';
$DATADIR  = '/usr/share/nmap';

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
        '--script'      => "scripts",
        '--stylesheet'  => "$BASEDIR/xslt/servicesTable.xsl",
        'refreshPeriod' => 60,
        'sudo'          => true,
    ],
];