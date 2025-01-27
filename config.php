<?php

$port         = (($_SERVER['REQUEST_SCHEME'] == "http" && $_SERVER['SERVER_PORT'] == 80) || ($_SERVER['REQUEST_SCHEME'] == "https" && $_SERVER['SERVER_PORT'] == 443)) ? "" : ":{$_SERVER['SERVER_PORT']}";
$BASEDIR      = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}$port" . dirname($_SERVER['SCRIPT_NAME']);
$SCANSDIR     = 'scans';
$TEMPLATESDIR = "templates";
$NMAP         = 'sudo nmap'; # nmap command, E.g. 'nmap', 'sudo nmap' for root privileges or '/usr/bin/nmap' if not in PATH
$NMAPDIR      = dirname(`which nmap`) . "/../share/nmap";
$DATADIR      = ".";
$SCRIPTARGS   = "script-args.ini";

$presets = [
    "default" => [
        '-PS'           => 'microsoft-ds',
        '-F'            => true,
        '-T'            => 5,
        '--stylesheet'  => "lanScan",
        'refreshPeriod' => 60,
        #'sudo'          => false,
    ],
    "host" => [
        '-Pn'           => true,
        '-F'            => true,
        '-sV'           => true,
        '-T'            => 5,
        '--script'      => "http-info,smb-shares-size",
        '--stylesheet'  => "hostScan",
        'refreshPeriod' => 60,
        #'sudo'          => true,
    ],
];
