#!/bin/bash

###
#
# Scan un réseau avec nmap pour créer un fichier de configuration
#
###

echo "Nom du site ?"
read site
echo "Adresse réseau CIDR (xxx.xxx.xxx.xxx/xx) ?"
read network
nmap --script smb-enum-shares.nse -oX "scans/$site.xml" $network
xsltproc --stringparam site "$site" --stringparam network $network toyaml.xsl "scans/$site.xml" > "confs/$site.yaml"
