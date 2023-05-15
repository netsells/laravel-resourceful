<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

/** @var Config $config */
$config = require_once join(DIRECTORY_SEPARATOR, [__DIR__, 'vendor', 'netsells', 'code-standards-laravel', 'phpcsfixer', 'config.php']);

$rules = array_merge_recursive($config->getRules(), [
    // set project specific rules here
]);

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return $config
    ->setFinder($finder)
    ->setRules($rules);
