#!/usr/bin/env bash

changes=$(git diff --name-only --diff-filter=ACMRT --exit-code HEAD~1)
for file in $changes
do
    if [[ ${file##*.} != "php" ]];then
      continue
    fi
    check_style "${file}"
done
