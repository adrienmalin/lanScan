#!/usr/bin/env bash

DIR="$(dirname -- "$0")"
conf="$1"

site="$(basename ${conf/.yaml/})"
php "$DIR/nmap_cmd.php" $conf | sh
mv "$DIR/scans/.~$site.xml" "$DIR/scans/$site.xml"