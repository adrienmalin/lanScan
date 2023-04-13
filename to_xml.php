<?php
$file = $argv[1];
$site = basename($file, ".yaml");

$conf = yaml_parse_file($file);

$xml = new SimpleXMLElement(<<<XML
<?xml version="1.0"?>
<?xml-stylesheet href='../results.xsl' type='text/xsl'?>
<lanScanConf scanpath="scans/$site.xml"/>
XML
);

foreach ($conf as $key => $value) {
    if ($key == "site") {
        $xml->addAttribute("site", $value);
    } else {
        $xmlGroup = $xml->addChild("group");
        $xmlGroup->addAttribute("name", $key);
        foreach($value as $hostaddress => $servicesList) {
            $xmlHost = $xmlGroup->addChild("host");
            $xmlHost->addAttribute("address", $hostaddress);
            if ($servicesList) foreach ($servicesList as $service) {
                $xmlService = $xmlHost->addChild("service");
                $xmlService->addAttribute("name", $service);
            }
        }
    }
}

echo $xml->asXML();
?>
