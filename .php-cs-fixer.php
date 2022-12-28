<?php

/*
 * This file is part of the flower-allure/composer-utils.
 * (c) flower-allure <i@flower-allure.me>
 * This source file is subject to the LGPL license that is bundled.
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$header = <<<EOF
    This file is part of the flower-allure/composer-utils.
    (c) flower-allure <i@flower-allure.me>
    This source file is subject to the LGPL license that is bundled.
    EOF;

$rules = [
    '@PSR2'                                 => true,
    '@Symfony'                              => true,
    '@PHP82Migration'                       => true,
    'array_syntax'                          => ['syntax' => 'short'],
    'header_comment'                        => ['header' => $header],
    'php_unit_strict'                       => true,
    'combine_consecutive_unsets'            => true,
    'no_useless_else'                       => true,
    'class_attributes_separation' => [
        'elements' => [
            'const' => 'one',
            'method' => 'one',
            'property' => 'one',
            'trait_import' => 'none',
            'case' => 'none'
        ],
    ],
    'no_useless_return'                     => true,
    'concat_space'                          => ['spacing' => 'one'],
];

$finder = Finder::create()
    ->exclude('*/vendor')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->in(__DIR__);

return (new Config())
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ->setFinder($finder);
