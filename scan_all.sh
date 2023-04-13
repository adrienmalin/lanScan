#!/usr/bin/env bash

DIR="$(dirname -- "$0")"

mkdir -p "$DIR/scans"
mkdir -p "$DIR/site"

for conf in "$DIR/confs/*.yaml"
do
    site="basename ${conf/.yaml/}"
    php "$DIR/to_xml.php" $conf > "$DIR/site/$site.xml"
    php "$DIR/nmap_cmd.php" $conf | sh
    mv "$DIR/scans/.~$site.xml" "$DIR/scans/$site.xml"
done
