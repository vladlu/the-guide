#!/bin/sh

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
cd $SCRIPTPATH


for file in ../assets/admin/css/*.prod.css
do
  mv "$file" "${file/.prod.css/.css}"
done



for file in ../assets/admin/js/*.prod.js
do
  mv "$file" "${file/.prod.js/.js}"
done



for file in ../assets/public/js/*.prod.js
do
  mv "$file" "${file/.prod.js/.js}"
done


sed -i '' -e "s/define( 'DEV_MODE', false )/define( 'DEV_MODE', true )/g" ../the-guide.php
