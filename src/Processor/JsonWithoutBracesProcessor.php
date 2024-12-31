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

final class JsonWithoutBracesProcessor implements ProcessorInterface
{
    public function parse(string $string): array
    {
        if (str_contains($string, '":')) {
            $string = '{'.$string.'}';
        }

        return (array) json_decode($string, true);
    }

    public function dump(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        $result = (string) json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if (str_starts_with($result, '{') && str_ends_with($result, '}')) {
            $result = substr($result, 1, -1);
        }

        return $result;
    }
}
