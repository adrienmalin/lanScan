<?php

$port = (($_SERVER['REQUEST_SCHEME'] == "http" && $_SERVER['SERVER_PORT'] == 80) || ($_SERVER['REQUEST_SCHEME'] == "https" && $_SERVER['SERVER_PORT'] == 443)) ? "" : ":{$_SERVER['SERVER_PORT']}";
$BASEDIR  = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}$port" . dirname($_SERVER['SCRIPT_NAME']);
$SCANSDIR = 'scans';
$NMAPDIR  = dirname(`which nmap`) . "/../share/nmap";
$DATADIR  = "datadir";

$presets = [
    "default" => [
        '-PS'           => 'microsoft-ds',
        '-F'            => true,
        '-T'            => 5,
        '--stylesheet'  => "$BASEDIR/templates/hostsTable.xsl",
        'refreshPeriod' => 60,
        'sudo'          => false,
    ],
    "host" => [
        '-Pn'           => true,
        '-F'            => true,
        '-sV'           => true,
        '-T'            => 5,
        '--script'      => "http-info,smb-shares-size",
        '--stylesheet'  => "$BASEDIR/templates/servicesTable.xsl",
        'refreshPeriod' => 60,
        'sudo'          => true,
    ],
];
