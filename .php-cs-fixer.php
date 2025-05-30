<?php

use Ergebnis\PhpCsFixer\Config;
use Ergebnis\PhpCsFixer\Config\Rules;
use Ergebnis\PhpCsFixer\Config\RuleSet\Php84;

$header = <<<'EOF'
Copyright (c) 2025 Kai Sassnowski

For the full copyright and license information, please view
the LICENSE file that was distributed with this source code.

@see https://github.com/ksassnowski/utgars-chronicles.app
EOF;

$ruleSet = Php84::create()
    ->withHeader($header)
    ->withRules(Rules::fromArray([
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
    ]));

$config = Config\Factory::fromRuleSet($ruleSet);

$config->getFinder()->in([__DIR__.'/app', __DIR__.'/tests']);
$config->setCacheFile(__DIR__.'/.build/php-cs-fixer/.php-cs-fixer.cache');

return $config;
