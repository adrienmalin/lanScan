<?php
if (!file_exists("scans")) mkdir("scans");

foreach (scandir("./site") as $file) {
    if (strrpos($file, ".yaml")) {
        $site = str_replace(".yaml", "", $file);
        $yaml = yaml_parse_file("site/$file");

        $targets = [];
        $services = [];

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
                if ($hosts) foreach($hosts as $hostaddress => $servicesList) {
                    $targets[$hostaddress] = true;
                    $xmlHost = $xmlGroup->addChild("host");
                    $xmlHost->addAttribute("address", $hostaddress);
                    if ($servicesList) foreach ($servicesList as $service) {
                        $services[$service] = true;
                        $xmlService = $xmlHost->addChild("service");
                        $xmlService->addAttribute("name", $service);
                    }
                }
            }
        }

        $targets = array_keys($targets);
        $services = array_keys($services);
        $xml->asXML("site/$site.xml");

        exec("nmap -v -Pn -p ".join($services, ",")." --script smb-enum-shares.nse -oX 'scans/$site.xml' ".join($targets, " ")."\n");
    }
};

?>
