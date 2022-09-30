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
        '@PSR12:risky' => true,
        'header_comment' => [
            'header' => $header,
        ],
    ])
    ->setLineEnding("\n")
    ->setUsingCache(false)
    ->setFinder(PhpCsFixer\Finder::create()->in([__DIR__.'/src', __DIR__.'/tests']))
;
