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

final class JsonProcessor implements ProcessorInterface
{
    public function parse($string)
    {
        return json_decode($string);
    }

    public function dump($data)
    {
        if (is_array($data) && empty($data)) {
            return '';
        }

        return json_encode($data);
    }
}
