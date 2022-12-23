<?php

/*
 * This file is part of the flower-allure/composer-utils.
 * (c) flower-allure <i@flower-allure.me>
 * This source file is subject to the LGPL license that is bundled.
 */

namespace FlowerAllure\ComposerUtils\Tests;

phpinfo();

$arr1 = [];
for ($i = 1; $i <= 10; $i++) {
    $arr1[] = $i;
}
$arr2 = [];
foreach ($arr1 as $value) {
    $arr2[] = $value;
}

class XDebug
{
}
