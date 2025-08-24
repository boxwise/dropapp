<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'templates/templates_c',
        'node_modules',
        'cache',
        'logs',
        'tmp',
        'build',  // Exclude build directory with jsmin
    ])
    ->notPath([
        'pdf/fpdf.php',             // Third-party FPDF library
        'cypress-session.php',      // Test file that might have specific formatting
    ])
    ->notName('*.tpl')
    ->name('*.php');

$config = new PhpCsFixer\Config();
return $config
    ->setRules([
        '@PhpCsFixer' => true,
        // Override specific rules that are too strict for this legacy codebase
        'operator_linebreak' => [
            'only_booleans' => true,
            'position' => 'beginning'
        ],
        'string_implicit_backslashes' => false,  // Disable for legacy FPDF compatibility
        'no_extra_blank_lines' => [
            'tokens' => [
                'break',
                'continue',
                'extra',
                'return',
                'throw',
                'use',
                'parenthesis_brace_block',
                'square_brace_block',
                'curly_brace_block'
            ]
        ],
    ])
    ->setFinder($finder);
