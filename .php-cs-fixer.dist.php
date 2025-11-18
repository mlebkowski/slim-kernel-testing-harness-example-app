<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests');

$rules = [
    "@PSR12" => true,
    "@PSR12:risky" => true,
    'braces_position' => [
        'classes_opening_brace' => 'same_line',
        'functions_opening_brace' => 'same_line',
        'control_structures_opening_brace' => 'same_line',
        'anonymous_classes_opening_brace' => 'same_line',
        'anonymous_functions_opening_brace' => 'same_line',
    ],
    'blank_line_between_import_groups' => false,
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'method_chaining_indentation' => true,
    'no_extra_blank_lines' => [
        'tokens' => [
            'attribute',
            'break',
            'case',
            'comma',
            'continue',
            'curly_brace_block',
            'default',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'switch',
            'throw',
            'use',
        ],
    ],
];

return (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules($rules)
    ;
