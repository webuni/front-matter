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

namespace Webuni\FrontMatter\Tests;

use Webuni\FrontMatter\Document;
use Webuni\FrontMatter\FrontMatter;
use Webuni\FrontMatter\Processor\JsonProcessor;
use Webuni\FrontMatter\Processor\JsonWithoutBracesProcessor;
use Webuni\FrontMatter\Processor\NeonProcessor;
use Webuni\FrontMatter\Processor\TomlProcessor;

class FrontMatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getYaml
     */
    public function testYaml($string, $data, $content)
    {
        $frontMatter = new FrontMatter();
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
        $this->assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getSeparator
     */
    public function testYamlWithCustomSeparator($string, $data, $content)
    {
        $frontMatter = new FrontMatter(null, '<!--', '-->');
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
        $this->assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getJson
     */
    public function testJson($string, $data, $content)
    {
        $frontMatter = new FrontMatter(new JsonProcessor());
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
        $this->assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getPlainJson
     */
    public function testPlainJson($string, $data, $content)
    {
        $frontMatter = new FrontMatter(new JsonWithoutBracesProcessor(), '{', '}');
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
        $this->assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getYaml
     */
    public function testNeon($string, $data, $content)
    {
        $frontMatter = new FrontMatter(new NeonProcessor());
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
        $this->assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getToml
     */
    public function testToml($string, $data, $content)
    {
        $frontMatter = new FrontMatter(new TomlProcessor());
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);

        $this->expectException(\BadMethodCallException::class);

        $this->assertEquals($string, $frontMatter->dump($document));
    }

    public function getYaml()
    {
        return [
            ['foo', [], 'foo'],
            ["---\nfoo: bar\n---\n", ['foo' => 'bar'], ''],
            ["---\nfoo: bar\n---\ntext", ['foo' => 'bar'], 'text'],
        ];
    }

    public function getSeparator()
    {
        return [
            ['foo', [], 'foo'],
            ["<!--\nfoo: bar\n-->\n", ['foo' => 'bar'], ''],
            ["<!--\nfoo: bar\n-->\ntext", ['foo' => 'bar'], 'text'],
        ];
    }

    public function getJson()
    {
        return [
            ['foo', [], 'foo'],
            ["---\n{\"foo\":\"bar\"}\n---\n", ['foo' => 'bar'], ''],
            ["---\n{\"foo\":\"bar\"}\n---\ntext", ['foo' => 'bar'], 'text'],
        ];
    }

    public function getPlainJson()
    {
        return [
            ['foo', [], 'foo'],
            ["{\n\"foo\":\"bar\"\n}\n", ['foo' => 'bar'], ''],
            ["{\n\"foo\":\"bar\"\n}\ntext", ['foo' => 'bar'], 'text'],
        ];
    }

    public function getToml()
    {
        return [
            ['foo', [], 'foo'],
            ["---\nfoo = 'bar'\n---\n", ['foo' => 'bar'], ''],
            ["---\nfoo = 'bar'\n---\ntext", ['foo' => 'bar'], 'text'],
        ];
    }

    private function assertDocument($data, $content, Document $document)
    {
        $this->assertEquals($data, $document->getData());
        $this->assertEquals($content, $document->getContent());
    }
}
