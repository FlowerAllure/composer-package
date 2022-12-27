#!/usr/bin/env bash

EXIT_CODE=0
PHP_CS_FIXER_IGNORE_ENV=1
START_PATH=$(cd `dirname ${0}`; pwd)

# cd to project root path
cd "${START_PATH}/.."

check_style() {
    command="./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer $1 && git diff --exit-code --color=always $1"
    echo $command
#  exec_command "${command}" "$1"
}

exec_command() {
    command=$1
    file=$2
    ext=${file##*.}
    if [[ ${ext} = "php" ]];then
        eval "${command}"
        status=$?
        if [[ ${status} -ne 0 ]];then
            EXIT_CODE=${status}
        fi
    fi
}

ci_check() {
    changes=$(git diff --name-only --diff-filter=ACMRT --exit-code HEAD~1)
    for file in $changes
    do
        if [[ ${file##*.} != "php" ]];then
          continue
        fi
        check_style "${file}"
    done
}

usage() {
    cat << EOF
Usage: bash `basename $0` [option] [path]

option:
    --check
        used for ci to check commit whether is valid or not, this command will automatically fix php coding style
EOF
}

case "$1" in
    "--check")
        ci_check
        ;;
    *)
        usage
    ;;
esac
