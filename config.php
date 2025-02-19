<?php
$NMAP           = "sudo nmap"; # nmap command, E.g. 'nmap', 'sudo nmap' for root privileges or '/usr/bin/nmap' if not in PATH
$NMAPDIR        = dirname(`which nmap`) . "/../share/nmap";
$port           = (($_SERVER['REQUEST_SCHEME'] == "http" && $_SERVER['SERVER_PORT'] == 80) || ($_SERVER['REQUEST_SCHEME'] == "https" && $_SERVER['SERVER_PORT'] == 443)) ? "" : ":{$_SERVER['SERVER_PORT']}";
$BASEDIR        = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}$port" . dirname($_SERVER['SCRIPT_NAME']);
$SCANSDIR       = "scans";
$STYLESHEETSDIR = "stylesheets";
$DATADIR        = ".";
$SCRIPTARGSFILE = "script-args.ini";
$COMMONOPTIONS  = [
    "--datadir"          => $DATADIR,
    "--script-args-file" => $SCRIPTARGSFILE,
];
$PRESETS           = [
    "lanScan" => [
        "-PS"          => "microsoft-ds",
        "-F"           => true,
        "-T"           => 4,
        "--script"     => "http-info,smb-shares-size",
        "--stylesheet" => "lanTable.xsl",
    ],
    "host" => [
        "-A"           => true,
        "-T"           => 5,
        "--script"     => "http-info,smb-shares-size",
        "--stylesheet" => "hostDetails.xsl",
    ],
];
