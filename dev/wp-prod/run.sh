#!/usr/bin/env bash

set -Eeuo pipefail
trap 'echo >&2 "ERROR on line $LINENO ($(tail -n+$LINENO $0 | head -n1)). Terminated."' ERR
trap '[ $? = 0 ] && echo "Done." ' EXIT

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
version=$(cat "version")

cd "$SCRIPTPATH"



install_wp-prod() {
    echo -e "Getting wp-prod $version...\n"
    wget "https://github.com/vladlu/wp-prod/archive/$version.tar.gz"
    tar -xzf "$version.tar.gz"
    rm -f "$version.tar.gz"
    mv "wp-prod-$version" "wp-prod"
}


run_wp-prod() {
    wp-prod/bin/run.sh
}


uninstall_wp-prod() {
    "$SCRIPTPATH/uninstall.sh"
}



##
# Runs wp-prod
##

if [ ! -d "$SCRIPTPATH/wp-prod" ]; then
    install_wp-prod
fi


##
# Checks for the version
##

current_version=$(cat "wp-prod/version")
specified_version=$(cat version)
if [[ "$current_version" != "$specified_version" ]]; then
    echo -e "The currently installed version ($current_version) differs from the specified ($specified_version).
Deleting it and installing the specified version.. \n"

    # If they differ, deletes the current version and installs the specified

    uninstall_wp-prod
    install_wp-prod
fi


run_wp-prod