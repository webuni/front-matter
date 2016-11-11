<?php

/*
 * This is part of the webuni/front-matter package.
 *
 * (c) Martin Hasoň <martin.hason@gmail.com>
 * (c) Webuni s.r.o. <info@webuni.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webuni\FrontMatter\Processor;

use Symfony\Component\Yaml\Yaml;

final class YamlProcessor implements ProcessorInterface
{
    private $yaml;

    public function __construct(Yaml $yaml = null)
    {
        $this->yaml = $yaml ?: new Yaml();
    }

    public function parse($string)
    {
        return $this->yaml->parse($string);
    }

    public function dump($data)
    {
        if (is_array($data) && empty($data)) {
            return '';
        }

        return $this->yaml->dump($data);
    }
}
