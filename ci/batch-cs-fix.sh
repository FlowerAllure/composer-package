#!/bin/bash

START_PATH=$(cd `dirname ${0}`; pwd)
cd "${START_PATH}/.."

PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php ./
git diff --exit-code --color=always
status=$?
if [[ $? -ne 0 ]]; then
    echo -e "\033[1;33m🎨 请更正代码中的格式错误\033[0m"
    echo $status
    exit $status
fi
