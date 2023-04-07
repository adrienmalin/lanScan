<?php
$yaml = yaml_parse_file($argv[1]);
$xml = new SimpleXMLElement("<lanScanConf></lanScanConf>");

foreach ($yaml as $groupName => $hosts) {
    $xmlGroup = $xml->addChild("group");
    $xmlGroup->addAttribute("name", $groupName);
    if ($hosts) foreach ($hosts as $hostName => $services) {
        $xmlHost = $xmlGroup->addChild("host");
        $xmlHost->addAttribute("name", $hostName);
        if ($services) foreach ($services as $service) {
            $xmlHost->addChild("service");
            $xmlHost->addAttribute("name", $service);
        }
    }
}

echo $xml->asXML();
?>
