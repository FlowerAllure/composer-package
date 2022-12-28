#!/usr/bin/env bash

check_style() {
    command="./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer $1 && git diff --exit-code --color=always $1"
    echo $command
#  exec_command "${command}" "$1"
}

START_PATH=$(cd `dirname ${0}`; pwd)
cd "${START_PATH}/.."

changes=$(git diff --name-only --diff-filter=ACMRT --exit-code HEAD~1)
for file in $changes
do
    check_style "${file}"
done
