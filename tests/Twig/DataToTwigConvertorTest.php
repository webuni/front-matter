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

namespace Webuni\FrontMatter\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use Webuni\FrontMatter\Twig\DataToTwigConvertor;

final class DataToTwigConvertorTest extends TestCase
{
    private array $data;

    protected function setUp(): void
    {
        $this->data = Yaml::parse((string) file_get_contents(__DIR__.'/data.yaml'), Yaml::PARSE_DATETIME);
    }

    public function testNothing(): void
    {
        $convertor = DataToTwigConvertor::nothing();
        self::assertEquals('', $convertor($this->data));
    }

    public function testVars(): void
    {
        $convertor = DataToTwigConvertor::vars();
        $twig = file_get_contents(__DIR__.'/templates/vars.twig');
        self::assertEquals($twig, $convertor($this->data));
    }

    public function testOptionalVars(): void
    {
        $convertor = DataToTwigConvertor::vars(false);
        $twig = file_get_contents(__DIR__.'/templates/optionalvars.twig');
        self::assertEquals($twig, $convertor($this->data));
    }

    public function testVar(): void
    {
        $convertor = DataToTwigConvertor::var('parameters');
        $twig = file_get_contents(__DIR__.'/templates/var.twig');
        self::assertEquals($twig, $convertor($this->data));
    }
}
