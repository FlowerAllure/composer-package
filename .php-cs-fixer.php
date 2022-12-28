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
    'array_syntax'                          => [
        'syntax' => 'short',
    ],
    'header_comment' => [
        'header' => $header,
    ],
    'php_unit_construct'                    => true,
    'php_unit_strict'                       => true,
    'no_empty_comment' => false,
    'no_extra_blank_lines'                  => [
        'tokens' => [
            'attribute', 'break', 'case', 'continue', 'curly_brace_block',
            'default', 'extra', 'parenthesis_brace_block', 'return',
            'square_brace_block', 'switch', 'throw', 'use', 'use_trait',
        ],
    ],
    'no_unneeded_control_parentheses'       => false,
    'not_operator_with_successor_space'     => true,
    'phpdoc_align'                          => [
        'tags' => ['param']
    ],
    'phpdoc_no_empty_return'                => false,
    'phpdoc_order'                          => true,
    'combine_consecutive_unsets'            => true,
    'no_useless_else'                       => true,
    'ordered_imports'                       => [
        'sort_algorithm' => 'length',
        'imports_order' => ['const', 'class', 'function'],
    ],
    'class_attributes_separation' => [
        'elements' => [
            'const' => 'one',
            'method' => 'one',
            'property' => 'one',
            'trait_import' => 'none',
            'case' => 'none',
        ],
    ],
    'no_whitespace_before_comma_in_array'   => true,
    'no_useless_return'                     => true,

    'single_quote' => [
        'strings_containing_single_quote_chars' => true,
    ],
    'whitespace_after_comma_in_array'       => [
        'ensure_single_space' => true,
    ],
    'ternary_to_null_coalescing'            => true,
    'ternary_operator_spaces'               => true,
    'no_trailing_whitespace'                => true,
    'no_spaces_inside_parenthesis'          => true,
    'no_unused_imports'                     => true,
    'concat_space'                          => ['spacing' => 'one'],
    'space_after_semicolon'                 => ['remove_in_empty_for_expressions' => true],
    'no_empty_statement'                    => true,
    'trim_array_spaces'                     => true,
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
