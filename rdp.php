<?php

$host = filter_input(INPUT_GET, 'v', FILTER_VALIDATE_DOMAIN) ?: filter_input(INPUT_GET, 'v', FILTER_VALIDATE_IP);
if (!$host) {
    exit();
}

header('Content-Disposition: attachment; filename='.str_replace(':', '_', $host).'.rdp');
header('Content-Type: application/rdp');
echo "full address:s:$host\n";
