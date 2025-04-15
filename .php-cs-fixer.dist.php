<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'no_unused_imports' => true,
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_extra_blank_lines' => true,
        'no_blank_lines_after_class_opening' => true,
        'blank_lines_before_namespace' => true,
        'no_blank_lines_after_phpdoc' => true,
        'single_blank_line_at_eof' => true,
        'single_line_empty_body' => true,
        'braces' => [
            'position_after_anonymous_constructs' => 'next',
            'position_after_control_structures' => 'next'
        ],
        'blank_line_between_import_groups' => false,

    ])
    ->setFinder($finder)
    ;