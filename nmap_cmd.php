<?php
$file = $argv[1];
$site = basename($file, ".yaml");
$__DIR__ = __DIR__;

$conf = yaml_parse_file($file);

$targets = [];
$services = [];

foreach ($conf as $key => $value) {
    if ($key != "site") {
        foreach($value as $hostaddress => $servicesList) {
            $targets[$hostaddress] = true;
            if ($servicesList) foreach ($servicesList as $service) {
                $services[$service] = true;
            }
        }
    }
}

$targets = join(array_keys($targets), " ");
$services = join(array_keys($services), ",");

echo ("nmap -v -Pn -p $services --script smb-enum-shares,$__DIR__/nmap -oX $__DIR__/scans/.~$site.xml $targets");
?>
