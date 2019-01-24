#!/bin/sh

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
cd $SCRIPTPATH


for file in ../admin/styles/*.css
do
  mv "$file" "${file/.css/.prod.css}"
done



for file in ../admin/js/*.js
do
  mv "$file" "${file/.js/.prod.js}"
done



for file in ../public/js/*.js
do
  mv "$file" "${file/.js/.prod.js}"
done


sed -i '' -e "s/define( 'DEV_MODE', true )/define( 'DEV_MODE', false )/g" ../the-guide.php



cd webpack

cp node_modules/@babel/polyfill/dist/polyfill.min.js ../../libs/babel-polyfill/babel-polyfill.js

node code_to_run.js