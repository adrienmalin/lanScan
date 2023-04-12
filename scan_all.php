<?php
if (! function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool {
        $needle_len = strlen($needle);
        return ($needle_len === 0 || 0 === substr_compare($haystack, $needle, - $needle_len));
    }
}

$__DIR__ = __DIR__;

if (!file_exists("$__DIR__/scans")) mkdir("$__DIR__/scans");
if (!file_exists("$__DIR__/site")) mkdir("$__DIR__/site");

foreach (scandir("$__DIR__/confs") as $file) {
    if (str_ends_with($file, ".yaml")) {
        $site = str_replace(".yaml", "", $file);
        $yaml = yaml_parse_file("$__DIR__/confs/$file");

        $targets = [];
        $services = [];

        $xml = new SimpleXMLElement(<<<XML
<?xml version="1.0"?>
<?xml-stylesheet href='../results.xsl' type='text/xsl'?>
<lanScanConf/>
XML
);
        $xml->addAttribute("scanpath", "scans/$site.xml");
    
        foreach ($yaml as $key => $value) {
            if ($key == "site") {
                $xml->addAttribute("site", $value);
            } else {
                $xmlGroup = $xml->addChild("group");
                $xmlGroup->addAttribute("name", $key);
                foreach($value as $hostaddress => $servicesList) {
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

        $targets = join(array_keys($targets), " ");
        $services = join(array_keys($services), ",");

        `nmap -v -Pn -p $services --script smb-enum-shares,'$__DIR__/nmap' -oX '$__DIR__/scans/tmp.xml' $targets && mv '$__DIR__/scans/tmp.xml' '$__DIR__/scans/$site.xml'`;

        $xml->asXML("$__DIR__/site/$site.xml");
    }
}
?>
