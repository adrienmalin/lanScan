<?php
$port           = (($_SERVER['REQUEST_SCHEME'] == "http" && $_SERVER['SERVER_PORT'] == 80) || ($_SERVER['REQUEST_SCHEME'] == "https" && $_SERVER['SERVER_PORT'] == 443)) ? "" : ":{$_SERVER['SERVER_PORT']}";
$BASEDIR        = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}$port" . dirname($_SERVER['SCRIPT_NAME']);
$SCANSDIR       = "scans";
$STYLESHEETSDIR = "stylesheets";

$lanScanCmd  = "sudo nmap -PSmicrosoft-ds -F -T5 --datadir . --script http-info,smb-shares-size --script-args-file script-args.ini -oX - --stylesheet $BASEDIR/$STYLESHEETSDIR/lanScan.xsl";
$hostScanCmd = "sudo nmap -A -T5 --datadir . --script http-info,smb-shares-size --script-args-file script-args.ini -oX - --stylesheet $BASEDIR/$STYLESHEETSDIR/hostScan.xsl";