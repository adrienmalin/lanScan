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
nmap --script smb-enum-shares.nse -oX "confs/$name.xml" $network
xsltproc toyaml.xsl "confs/$name.xml" > "confs/$name.yaml"
