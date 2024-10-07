<?php

$host = filter_input(INPUT_GET, 'v', FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) ?: filter_input(INPUT_GET, 'v', FILTER_VALIDATE_IP);
if (!$host) {
    exit();
}

$port = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT);
if ($port) {
    $host = "$host:$port";
}

header("Content-Disposition: attachment; filename=$host.rdp");
header('Content-Type: application/rdp');
echo "full address:s:$host\n";
