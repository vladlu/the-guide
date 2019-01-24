#!/bin/sh

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
cd $SCRIPTPATH



while IFS=, read -r path extension
do
	for file in "../$path/*.$extension"
	do
  		mv "$file" "${file/.$extension/.prod.$extension}"
	done
done < "files_to_cover.csv"



sed -i '' -e "s/define( 'DEV_MODE', true )/define( 'DEV_MODE', false )/g" ../the-guide.php



cd webpack


cp node_modules/@babel/polyfill/dist/polyfill.min.js ../../libs/babel-polyfill/babel-polyfill.js

node code_to_run.js