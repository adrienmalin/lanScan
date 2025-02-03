<?php

$BASEDIR = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}$port" . dirname($_SERVER['SCRIPT_NAME']);
$SCANDIR = "scans";
$STYLESHEETDIR = "stylesheets";

$lanScanCmd = "sudo nmap -PSmicrosoft-ds -F -T5 -oX - --stylesheet $BASEDIR/$STYLESHEETDIR/lanScan.xsl";