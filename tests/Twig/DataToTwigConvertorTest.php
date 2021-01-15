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

class DataToTwigConvertorTest extends TestCase
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data = Yaml::parse(file_get_contents(__DIR__.'/data.yaml'), Yaml::PARSE_DATETIME);
    }

    public function testNothing(): void
    {
        $convertor = DataToTwigConvertor::nothing();
        $this->assertEquals('', $convertor->convert($this->data));
    }

    public function testVars(): void
    {
        $convertor = DataToTwigConvertor::vars();
        $twig = '{% set foo = "bar" %}
{% set number = 1234 %}
{% set pi = 3.14159 %}
{% set date = (1464307200|date_modify(\'0sec\')) %}
{% set empty = null %}
{% set multiline = "Multiple\nLine\nString\n" %}
{% set object = {key: "value", datetime: (1605185652|date_modify(\'0sec\')), values: {0: "one", 1: "two", }, } %}
';
        $this->assertEquals($twig, $convertor->convert($this->data));
    }

    public function testOptionalVars(): void
    {
        $convertor = DataToTwigConvertor::vars(false);
        $twig = '{% set foo = foo is defined ? foo : "bar" %}
{% set number = number is defined ? number : 1234 %}
{% set pi = pi is defined ? pi : 3.14159 %}
{% set date = date is defined ? date : (1464307200|date_modify(\'0sec\')) %}
{% set empty = empty is defined ? empty : null %}
{% set multiline = multiline is defined ? multiline : "Multiple\nLine\nString\n" %}
{% set object = object is defined ? object : {key: "value", datetime: (1605185652|date_modify(\'0sec\')), values: {0: "one", 1: "two", }, } %}
';
        $this->assertEquals($twig, $convertor->convert($this->data));
    }

    public function testVar(): void
    {
        $convertor = DataToTwigConvertor::var('parameters');
        $twig = '{% set parameters = {foo: "bar", number: 1234, pi: 3.14159, date: (1464307200|date_modify(\'0sec\')), empty: null, multiline: "Multiple\nLine\nString\n", object: {key: "value", datetime: (1605185652|date_modify(\'0sec\')), values: {0: "one", 1: "two", }, }, }%}'."\n";
        $this->assertEquals($twig, $convertor->convert($this->data));
    }
}
