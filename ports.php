<?php

include_once 'config.php';

$nmap_services = file("$NMAP_DATADIR/nmap-services");
foreach ($nmap_services as $service) {
    $comment = strpos($service, '#');
    if ($comment !== 0) {
        [$name, $port] = explode("\t", $service);
        [$portid, $protocole] = explode('/', $port);
        $protocole = strtoupper(substr($protocole, 0, 1));
        echo "<option value=$portid></option><option value=$protocole:$portid></option><option value=$name></option>\n";
    }
}
