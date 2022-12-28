#!/bin/bash

params=(${@})

exit_command() {
    echo -e "\033[1;33m参数错误\033[0m"
    exit $1
}

usage() {
    cat << EOF
Usage: bash ./shell/build.sh --branch=master --services=service1,service2
EOF
}

branch_build() {
    git fetch --tags --progress
    commit=$(git rev-parse -q --verify origin/${1}^{commit})
    if [[ -z $commit ]];then
        exit_command $?
    fi
    git checkout -f ${commit}
}

parse_business() {
    case "$1" in
      "--branch")
        branch_build ${2}
        ;;
      *)
        usage
        ;;
    esac
}

parse_params() {
    OLD_IFS="$IFS"
    IFS="="
    local params=(${@})
    IFS="$OLD_IFS"
    local len=${#params[@]}
    if [ $len -ne 2 ]; then
        return
    fi
    parse_business ${params[0]} ${params[1]}
}

START_PATH=$(cd `dirname ${0}`; pwd)
cd "${START_PATH}/.."

for param in ${params[@]}
do
    parse_params $param
done
