#!/bin/bash

params=(${@})

exit_command() {
    echo -e "\033[1;33m🎨 需要一个单独的版本\033[0m"
    exit $1
}

usage() {
    cat << EOF
Usage: bash ./shell/build.sh --branch=master --services=service1,service2

branch_build function core code:
    git fetch && git checkout -f $(git rev-parse -q --verify origin/${1}^{commit})

and other model
    git fetch && git reset --hard origin/${1}

EOF
}

branch_build() {
    git config --global advice.detachedHead false
    git fetch --tags --progress
    commit=$(git rev-parse -q --verify origin/${1}^{commit})
    if [[ -z $commit ]];then
        exit_command $?
    fi
    git checkout -f ${commit}
}

# 分解参数
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

# 解析参数
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
