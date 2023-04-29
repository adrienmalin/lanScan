<?php
$file = $argv[1];
$site = basename($file, ".yaml");
$__DIR__ = __DIR__;

$conf = yaml_parse_file($file);

$xml = new DomDocument("1.0", "utf-8");
$xml->preserveWhiteSpace = false;
$xml->formatOutput = true;
$xml->appendChild($xml->createProcessingInstruction("xml-stylesheet", "href='../results.xsl' type='text/xsl'"));
$root = $xml->appendChild($xml->createElement("lanScan"));
$root->setAttribute("scanpath", "./scans/$site.xml");

function appendArray($document, $node, $array) {
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            foreach ($value as $vkey => $vvalue) {
                if (is_string($vkey)) {
                    if (is_array($vvalue)) {
                        $child = $document->createElement($vkey);
                        toXML($document, $child, $vvalue);
                    } else {
                        $child = $document->createElement($vkey, $vvalue);
                    }
                    $node->appendChild($child);
                } else {
                    if (is_array($vvalue)) {
                        $child = $document->createElement($key);
                        appendArray($document, $child, $vvalue);
                    } else {
                        $child = $document->createElement($key, $vvalue);
                    }
                    $node->appendChild($child);
                }
                
            }
        } else {
            $node->setAttribute($key, $value);
        }
    }
}

appendArray($xml, $root, $conf);

print $xml->saveXML();
?>
