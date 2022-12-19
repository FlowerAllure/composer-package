<?php
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR2'                                 => true,
    '@PHP82Migration'                       => true,
    'array_syntax'                          => [
        'syntax' => 'short'
    ],
    'combine_consecutive_unsets'            => true,
    'no_useless_else'                       => true,
    'ordered_imports'                       => [
        'sort_algorithm' => 'length',
        'imports_order' => ['const', 'class', 'function']
    ],
    'class_attributes_separation' => [
        'elements' => [
            'const' => 'one',
            'method' => 'one',
            'property' => 'one',
            'trait_import' => 'none',
            'case' => 'none'
        ],
    ],
    'no_whitespace_before_comma_in_array'   => true,
    'no_useless_return'                     => true,
    'no_extra_blank_lines'                  => [
        'tokens' => [
            'attribute', 'break', 'case', 'continue', 'curly_brace_block',
            'default', 'extra', 'parenthesis_brace_block', 'return',
            'square_brace_block', 'switch', 'throw', 'use', 'use_trait'
        ],
    ],
    'single_quote' => [
        'strings_containing_single_quote_chars' => true,
    ],
    'binary_operator_spaces' => [
        'default' => 'single_space',
    ],
    'whitespace_after_comma_in_array'       => [
        'ensure_single_space' => true
    ],
    'phpdoc_add_missing_param_annotation'   => true,
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
