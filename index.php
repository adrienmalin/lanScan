<?php
$site = filter_input(INPUT_GET, "site", FILTER_SANITIZE_STRING);
$site = escapeshellcmd($site);

if ($site and file_exists("confs/$site.yaml") and file_exists("scans/$site.xml")) {
    $conf = yaml_parse_file("confs/$site.yaml");
    $scan = simplexml_load_file("scans/$site.xml");
    require("results.php");
} else {
    require("ls.php");
}
?>
