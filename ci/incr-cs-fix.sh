#!/bin/bash

exit_command() {
    echo -e "\033[1;33m🎨 请更正代码中的格式错误\033[0m"
    exit $1
}

check_style() {
    PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php $1 && git diff --exit-code --color=always $1
    if [[ $? -ne 0 ]]; then
        exit_command $?
    fi
}

START_PATH=$(cd `dirname ${0}`; pwd)
cd "${START_PATH}/.."

# 和GitHub不兼容
changes=$(git diff --name-only --diff-filter=ACMRT --exit-code HEAD~1)
for file in $changes
do
    if [[ ${file##*.} != "php" ]];then
      continue
    fi
    check_style "${file}"
done
