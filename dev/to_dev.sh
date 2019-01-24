#!/bin/sh

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
cd $SCRIPTPATH



while IFS=, read -r path extension
do
	for file in "../$path/*.prod.$extension"
	do
  		mv "$file" "${file/.prod.$extension/.$extension}"
	done
done < "files_to_cover.csv"



sed -i '' -e "s/define( 'DEV_MODE', false )/define( 'DEV_MODE', true )/g" ../the-guide.php
