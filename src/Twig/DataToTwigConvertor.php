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
    /** @var callable(array): string */
    private $convertor;

    protected function __construct(callable $convertor)
    {
        $this->convertor = $convertor;
    }

    public function __invoke(array $data): string
    {
        $convertor = $this->convertor;

        return (string) $convertor($data);
    }

    /**
     * Data will not be written to the twig template.
     */
    public static function nothing(): self
    {
        return new self(static function (): string {
            return '';
        });
    }

    /**
     * @psalm-suppress MixedAssignment
     *
     * @param bool $force Define the value of the variable even if it is passed to the template
     */
    public static function vars(bool $force = true): self
    {
        return new self(static function (array $data) use ($force): string {
            $content = '';
            foreach ($data as $key => $value) {
                if (is_int($key)) {
                    // TODO add log
                    continue;
                }

                $value = ($force ? '' : "{$key} is defined ? {$key} : ").self::valueToTwig($value);
                $content .= "{% set {$key} = {$value} %}\n";
            }

            return $content;
        });
    }

    /**
     * Converts data to one twig variable.
     *
     * @param string $name  Variable name
     * @param bool   $force Define the value of the variable even if it is passed to the template
     */
    public static function var(string $name, bool $force = true): self
    {
        return new self(static function (array $data) use ($name, $force): string {
            $value = ($force ? '' : "{$name} is defined ? {$name} : ").self::valueToTwig($data);

            return "{% set {$name} = {$value} %}\n";
        });
    }

    /**
     * @psalm-suppress MixedAssignment
     */
    protected static function valueToTwig(mixed $value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return '('.$value->getTimestamp()."|date_modify('0sec'))";
        }

        if (is_array($value)) {
            $twig = '{';
            foreach ($value as $key => $val) {
                $twig .= "{$key}: ".static::valueToTwig($val).', ';
            }

            return $twig.'}';
        }

        return (string) json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
