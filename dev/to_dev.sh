#!/bin/sh

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
cd $SCRIPTPATH


for file in ../admin/styles/*.prod.css
do
  mv "$file" "${file/.prod.css/.css}"
done



for file in ../admin/js/*.prod.js
do
  mv "$file" "${file/.prod.js/.js}"
done



for file in ../public/js/*.prod.js
do
  mv "$file" "${file/.prod.js/.js}"
done


sed -i '' -e "s/define( 'DEV_MODE', false )/define( 'DEV_MODE', true )/g" ../the-guide.php
