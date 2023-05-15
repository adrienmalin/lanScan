#!/usr/bin/env bash

###
#
# Scan un réseau avec nmap pour créer un fichier de configuration
# Usage : ./discover <reseau> avec reseau en notation CIDR XXX.XXX.XXX.XXX/XX
#
###

if [ "$#" -ne 1 ]; then
    echo -e "Usage : ./discover <CIDR>\navec <CIDR> l'adresse réseau en notation CIDR (XXX.XXX.XXX.XXX/XX)"  >&2
    exit 1
fi

pushd "$(dirname -- "$0")" > /dev/null
network="$1"
site="${network/\//_}"

mkdir -p "scans"
nmap -F -oX "scans/$site.xml" $network
mkdir -p "configs"
xsltproc --stringparam network "$network" to_config.xsl "scans/$site.xml" > "configs/$site.yml"
php to_XML.php "configs/$site.yml" > "site/$site.xml"

popd > /dev/null