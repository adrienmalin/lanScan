#!/usr/bin/env bash

###
#
# Scan un réseau avec nmap pour créer un fichier de configuration
#
###

DIR="$(dirname -- "$0")"

echo "Nom du site ?"
read site
filename="${site/ /_}"
echo "Adresse réseau CIDR (xxx.xxx.xxx.xxx/xx) ?"
read network


nmap --script smb-enum-shares.nse -oX "scans/$filename.xml" $network
xsltproc --stringparam site "$site" --stringparam network $network toyaml.xsl "$DIR/scans/$filename.xml" > "$DIR/confs/$filename.yaml"
