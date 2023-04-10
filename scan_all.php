<?php
if (! function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool
    {
        $needle_len = strlen($needle);
        return ($needle_len === 0 || 0 === substr_compare($haystack, $needle, - $needle_len));
    }
}

if (!file_exists("scans")) mkdir("scans");

foreach (scandir("./site/") as $file) {
    if (str_ends_with($file, ".yaml")) {
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
        $xml->asXML("site/$site.xml");

        $targets = join(array_keys($targets), " ");
        $services = join(array_keys($services), ",");

        exec("nmap -v -Pn -p $services --script smb-enum-shares.nse,./http-status.nse,./http-favicon-url.nse --script-args=httpspider.maxpagecount=1 -oX 'scans/$site.xml' $targets\n");
    }
};

?>
