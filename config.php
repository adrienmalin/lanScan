<?php
$port           = (($_SERVER['REQUEST_SCHEME'] == "http" && $_SERVER['SERVER_PORT'] == 80) || ($_SERVER['REQUEST_SCHEME'] == "https" && $_SERVER['SERVER_PORT'] == 443)) ? "" : ":{$_SERVER['SERVER_PORT']}";
$BASEDIR        = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}$port" . dirname($_SERVER['SCRIPT_NAME']);
$SCANSDIR       = "scans";
$STYLESHEETSDIR = "stylesheets";

$NMAP = "sudo nmap"; # nmap command, E.g. 'nmap', 'sudo nmap' for root privileges or '/usr/bin/nmap' if not in PATH
$DATADIR = ".";
$SCRIPTARGSFILE = "script-args.ini";
$LANSCANOPTIONS  = "-PSmicrosoft-ds -F -T5 --datadir '$DATADIR' --script http-info,smb-shares-size --script-args-file '$SCRIPTARGSFILE'";
$HOSTSCANOPTIONS = "-A -T5 --datadir '$DATADIR' --script http-info,smb-shares-size --script-args-file '$SCRIPTARGSFILE'";
$CUSTOMSCANOPTIONS = "--datadir '$DATADIR' --script-args-file '$SCRIPTARGSFILE'";