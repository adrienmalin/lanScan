<?php
$file = $argv[1];
$site = basename($file, ".yaml");
$__DIR__ = __DIR__;

$conf = yaml_parse_file($file);

$xml = new SimpleXMLElement(<<<XML
<?xml version="1.0"?>
<?xml-stylesheet href='../results.xsl' type='text/xsl'?>
<lanScanConf scanpath="scans/$site.xml"/>
XML
);

$targets = [];
$services = [];

foreach ($conf as $key => $value) {
    if ($key == "site") {
        $xml->addAttribute("site", $value);
    } else {
        $xmlGroup = $xml->addChild("group");
        $xmlGroup->addAttribute("name", $key);
        foreach($value as $hostaddress => $servicesList) {
            $xmlHost = $xmlGroup->addChild("host");
            $xmlHost->addAttribute("address", $hostaddress);
            $targets[$hostaddress] = true;
            if ($servicesList) foreach ($servicesList as $service) {
                $xmlService = $xmlHost->addChild("service");
                $xmlService->addAttribute("name", $service);
                $services[$service] = true;
            }
        }
    }
}

$xml->asXML("site/$site.xml");

$targets = join(array_keys($targets), " ");
$services = join(array_keys($services), ",");

echo ("nmap -Pn -p $services --script $__DIR__/http-info.nse -oX $__DIR__/scans/.~$site.xml $targets");
?>
