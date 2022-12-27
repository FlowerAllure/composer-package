#!/bin/bash

params=(${@})

EXIT_CODE=0

exec_command() {
    command=$1
    eval ${command}
    status=$?
    if [[ ${status} -ne 0 ]];then
        EXIT_CODE=${status}
    fi
}

usage() {
    cat << EOF
Usage: bash --branch=master --services=service1,service2
EOF
}

branch() {
    exec_command 'git fetch --tags --progress'
    exec_command 'git rev-parse -q --verify origin/master^{commit}'
    local commit=exec_command 'git rev-parse -q --verify origin/master^{commit}'
    exec_command "git checkout -f ${commit}"
}

parse_business() {
    case "$1" in
      "--branch=")
        branch ${2}
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


exit;
echo


service supervisord restart
service supervisord start

exit;




rm -rf $DIR/bootstrap/cache/config.php $DIR/bootstrap/cache/services.php $DIR/bootstrap/cache/packages.php
cd $DIR
# shellcheck disable=SC1083
git fetch && COMMIT=`git rev-parse refs/remotes/origin/master^{commit}` && git checkout -f $COMMIT
/usr/local/php/bin/php $DIR/artisan config:cache
chmod 777 -R $DIR/bootstrap/ $DIR/storage/
ls $DIR/ | grep -v 'public' | xargs chown nginx:nginx -R
chown nginx:nginx $DIR/public
# shellcheck disable=SC2010
cd $DIR/public/ && ls $DIR/public/ | grep -v 'files' | xargs chown nginx:nginx -R



#!/bin/bash
cd /var/www/cris_project
git pull origin master
DIR=/var/www/cris30_project
rm -rf $DIR/bootstrap/cache/config.php $DIR/bootstrap/cache/services.php $DIR/bootstrap/cache/packages.php
cd $DIR
git pull origin master
/usr/local/php/bin/php $DIR/artisan config:cache
chmod 777 -R $DIR/bootstrap/ $DIR/storage/
# shellcheck disable=SC2010
ls $DIR/ | grep -v 'public' | xargs chown nginx:nginx -R
chown nginx:nginx $DIR/public
# shellcheck disable=SC2010
cd $DIR/public/ && ls $DIR/public/ | grep -v 'files' | xargs chown nginx:nginx -R


#!/bin/bash
cd /var/www/cris_project
git pull origin master
service supervisord stop
DIR=/var/www/cris30_project
rm -rf $DIR/bootstrap/cache/config.php $DIR/bootstrap/cache/services.php $DIR/bootstrap/cache/packages.php
cd $DIR
git pull origin master
/usr/local/php/bin/php $DIR/artisan config:cache
chmod 777 -R $DIR/bootstrap/ $DIR/storage/
# shellcheck disable=SC2010
ls $DIR/ | grep -v 'public' | xargs chown nginx:nginx -R
chown nginx:nginx $DIR/public
# shellcheck disable=SC2010
cd $DIR/public/ && ls $DIR/public/ | grep -v 'files' | xargs chown nginx:nginx -R
service supervisord start


#!/bin/bash
cd /var/www/cris_portal
git pull origin master
DIR=/var/www/cris30_portal
cd $DIR
git pull origin master
/usr/local/php/bin/php $DIR/artisan config:cache
/usr/local/php/bin/php $DIR/artisan migrate
chmod 777 -R $DIR/bootstrap/ $DIR/storage/
# shellcheck disable=SC2011
ls $DIR/ | xargs chown nginx:nginx -R
/usr/local/php/bin/php $DIR/artisan project:db
/usr/local/php/bin/php $DIR/artisan project:table
/usr/local/php/bin/php $DIR/artisan project:auth


