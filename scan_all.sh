#!/usr/bin/env bash

DIR="$(dirname -- $0)"

mkdir -p "$DIR"/scans
mkdir -p "$DIR"/site

for conf in "$DIR"/confs/*.yaml
do
    ./scan.sh "$conf"
done
