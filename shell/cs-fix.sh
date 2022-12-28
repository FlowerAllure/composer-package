#!/usr/bin/env bash

check_style() {
    PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php $1 && git diff --exit-code --color=always $1
    if [[ $? -ne 0 ]]; then
        exit $?
    fi
}

START_PATH=$(cd `dirname ${0}`; pwd)
cd "${START_PATH}/.."

changes=$(git diff --name-only --diff-filter=ACMRT --exit-code HEAD~1)
for file in $changes
do
    check_style "${file}"
done
