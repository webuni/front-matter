<?php

$header = <<<EOF
This is part of the webuni/front-matter package.

(c) Martin Hasoň <martin.hason@gmail.com>
(c) Webuni s.r.o. <info@webuni.cz>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        '@PSR12:risky' => true,
        '@PHP84Migration' => true,
        'header_comment' => [
            'header' => $header,
        ],
        'php_unit_attributes' => true,
        'php_unit_test_class_requires_covers' => false,
    ])
    ->setLineEnding("\n")
    ->setUsingCache(false)
    ->setFinder(PhpCsFixer\Finder::create()->in([__DIR__.'/src', __DIR__.'/tests']))
;
