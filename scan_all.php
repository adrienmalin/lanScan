<?php
if (!file_exists("scans")) mkdir("scans");

foreach (scandir("./confs") as $file) {
    if (strrpos($file, ".yaml")) {
        $site = str_replace(".yaml", "", $file);
        $conf = yaml_parse_file("confs/$file");

        $targets = [];
        $services = [];

        foreach ($conf as $sitename => $hosts) {
            foreach($hosts as $hostaddress => $servicesList) {
                $targets[$hostaddress] = true;
                foreach ($servicesList as $service) {
                    $services[$service] = true;
                }
            }
        }

        $targets = array_keys($targets);
        $services = array_keys($services);

        exec("nmap -v -Pn -p ".join($services, ",")." --script smb-enum-shares.nse -oX 'scans/$site.xml' ".join($targets, " "));
    }
};

?>
