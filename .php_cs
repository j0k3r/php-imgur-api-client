<?php

return Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    // use default SYMFONY_LEVEL and extra fixers:
    ->fixers(array(
        'concat_with_spaces',
        'ordered_use',
        'phpdoc_order',
        'strict',
        'strict_param',
        'short_array_syntax',
    ))
    ->finder(
        Symfony\CS\Finder::create()
            ->in(__DIR__)
            ->exclude(array('vendor'))
    )
;
