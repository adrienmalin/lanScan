<?php
$yaml = yaml_parse_file($argv[1]);
$site = str_replace(".yaml", "", basename($argv[1]));

$xml = new SimpleXMLElement(<<<XML
<?xml version="1.0"?>
<?xml-stylesheet href='../results.xsl' type='text/xsl'?>
<lanScanConf/>
XML);
$xml->addChild("scan path='scans/$site.xml'");

foreach ($yaml as $siteName => $groups) {
    $xml->addAttribute("name", $siteName);
    if ($groups) foreach ($groups as $groupName => $hosts) {
        $xmlGroup = $xml->addChild("group");
        $xmlGroup->addAttribute("name", $groupName);
        if ($hosts) foreach ($hosts as $hostName => $services) {
            $xmlHost = $xmlGroup->addChild("host");
            $xmlHost->addAttribute("address", $hostName);
            if ($services) foreach ($services as $service) {
                $xmlService = $xmlHost->addChild("service");
                $xmlService->addAttribute("name", $service);
            }
        }
}
}

$xml->asXML("site/$site.xml");
?>
