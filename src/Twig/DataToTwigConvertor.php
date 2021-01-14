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

namespace Webuni\FrontMatter\Twig;

class DataToTwigConvertor
{
    /** @var callable */
    private $convertor;

    protected function __construct(callable $convertor)
    {
        $this->convertor = $convertor;
    }

    /**
     * Data will not be written to the twig template
     *
     * @return static
     */
    public static function nothing(): self
    {
        return new static(function (array $data) {
            return '';
        });
    }

    /**
     * @param bool $force Define the value of the variable even if it is passed to the template
     * @return static
     */
    public static function vars(bool $force = true): self
    {
        return new static(function (array $data) use ($force) {
            $content = '';
            foreach ($data as $key => $value) {
                if (preg_match('/^[a-z][0-9a-z_]*$/i', $key)) {
                    $content .= "{% set $key = " . ($force ? '' : "$key is defined ? $key : ") . static::valueToTwig($value) . " %}\n";
                }
            }

            return $content;
        });
    }

    /**
     * Converts data to one twig variable
     *
     * @param string $name  Variable name
     * @param bool   $force Define the value of the variable even if it is passed to the template
     * @return static
     */
    public static function var(string $name, bool $force = true): self
    {
        return new static(function (array $data) use ($name, $force) {
            return "{% set $name = " . ($force ? '' : "$name is defined ? $name : ") . static::valueToTwig($data) . "%}\n";
        });
    }

    public function convert(array $data): string
    {
        $convertor = $this->convertor;

        return $convertor($data);
    }

    public static function valueToTwig($value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return '(' . $value->getTimestamp() . "|date_modify('0sec'))";
        } elseif (is_array($value)) {
            $twig = '{';
            foreach ($value as $key => $val) {
                $twig .= "$key: ".static::valueToTwig($val).", ";
            }
            return $twig."}";
        } else {
            return json_encode($value);
        }
    }
}
