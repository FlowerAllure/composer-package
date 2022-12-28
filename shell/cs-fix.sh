#!/usr/bin/env bash

exit_command() {
    echo -e "\033[1;33m参数错误\033[0m"
    exit $1
}

usage() {
    cat << EOF
Usage: bash ./shell/build.sh --branch=master --services=service1,service2
EOF
}

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
