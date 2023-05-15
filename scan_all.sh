#!/usr/bin/env bash

pushd "$(dirname -- $0)" > /dev/null

mkdir -p scans
mkdir -p site

for config in configs/*.yml
do
    site="$(basename ${config/.yml/})"
    echo "Scan $site"
    ./scan.sh "$site"
done

popd > /dev/null
