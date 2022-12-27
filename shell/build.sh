#!/bin/bash

params=(${@})

exec_command() {
    command=$1
    eval ${command}
    status=$?
    if [[ ${status} -ne 0 ]];then
        echo 22
        exit
    fi
}

usage() {
    cat << EOF
Usage: bash ./shell/build.sh --branch=master --services=service1,service2
EOF
}

branch_build() {
    exec_command 'git fetch --tags --progress'
#    exec_command "git rev-parse -q --verify origin/${1}^{commit}"
    local commit=$(exec_command "git rev-parse -q --verify origin/${1}^{commit}")
#    exec_command "git checkout -f ${commit}"
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
cd $START_PATH

for param in ${params[@]}
do
    parse_params $param
done
