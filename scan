#!/usr/bin/env bash

if [ "$#" -ne 1 ]; then
    echo "Usage: ./scan <config>"  >&2
    exit 1
fi

pushd "$(dirname -- "$0")" > /dev/null
site="$(basename ${1/.yml/})"

php "to_XML.php" "configs/$site.yml" > "site/$site.xml" \
&& eval $(xsltproc "nmap_cmd.xsl" "site/$site.xml") \
&& mv "scans/$site.xml.tmp" "scans/$site.xml"

popd  > /dev/null