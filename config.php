<?php

$BASEDIR = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}$port" . dirname($_SERVER['SCRIPT_NAME']);
$SCANSDIR = "scans";
$STYLESHEETSDIR = "stylesheets";

$lanScanCmd = "sudo nmap -PSmicrosoft-ds -F -T5 -oX - --stylesheet $BASEDIR/$STYLESHEETSDIR/lanScan.xsl";