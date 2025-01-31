<?php

$SCANDIR = "scans";
$STYLESHEETDIR = "stylesheets";

$lanScanCmd = "sudo nmap -PSmicrosoft-ds -F -T5 -oX - --stylesheet $STYLESHEETDIR/lanScan.xsl";