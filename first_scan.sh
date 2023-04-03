#!/bin/bash

###
#
# Scan un réseau avec nmap pour créer un fichier de configuration
#
###

echo "Nom du site ?"
read name
echo "Plage IP (xxx.xxx.xxx.xxx/xx) ?"
read network
nmap --script smb-enum-shares.nse -oX "scans/$name.xml" $network
xsltproc toyaml.xsl "scans/$name.xml" > "scans/$name.yaml"
