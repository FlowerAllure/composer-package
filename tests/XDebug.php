<?php

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
