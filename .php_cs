<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(array('vendor'));

return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'phpdoc_order' => true,
    ])
    ->setFinder($finder);
