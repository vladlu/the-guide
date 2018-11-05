#!/bin/sh

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
cd $SCRIPTPATH


for file in ../assets/admin/css/*.css
do
  mv "$file" "${file/.css/.prod.css}"
done



for file in ../assets/admin/js/*.js
do
  mv "$file" "${file/.js/.prod.js}"
done



for file in ../assets/public/js/*.js
do
  mv "$file" "${file/.js/.prod.js}"
done


sed -i '' -e "s/define( 'DEV_MODE', true )/define( 'DEV_MODE', false )/g" ../the-guide.php



cd webpack

cp node_modules/@babel/polyfill/dist/polyfill.min.js ../../assets/general/lib/babel-polyfill/babel-polyfill.js

node code_to_run.js