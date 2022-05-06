<?php

use Ergebnis\PhpCsFixer\Config;

$header = <<<EOF
Copyright (c) 2022 Kai Sassnowski

For the full copyright and license information, please view
the LICENSE file that was distributed with this source code.

@see https://github.com/ksassnowski/utgars-chronicles.app
EOF;

$config = Config\Factory::fromRuleSet(new Config\RuleSet\Php81($header), [
    'final_class' => false,
    'final_public_method_for_abstract_class' => false,
    'phpdoc_to_property_type' => false,
    'php_unit_test_class_requires_covers' => false,
    'class_attributes_separation' => [
        'elements' => [
            'const' => 'only_if_meta',
            'method' => 'one',
            'property' => 'one',
            'trait_import' => 'none',
        ],
    ],
]);

$config->getFinder()->in([__DIR__ . '/app', __DIR__ . '/tests']);
$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/.php-cs-fixer.cache');

return $config;
