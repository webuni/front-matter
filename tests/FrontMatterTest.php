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
use Webuni\FrontMatter\Processor\NeonProcessor;
use Webuni\FrontMatter\Processor\TomlProcessor;

class FrontMatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getYaml
     */
    public function testParseYaml($string, $data, $content)
    {
        $frontMatter = new FrontMatter();
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
    }

    /**
     * @dataProvider getYaml
     */
    public function testDumpYaml($string, $data, $content)
    {
        $frontMatter = new FrontMatter();
        $document = new Document($content, $data);

        $this->assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getSeparator
     */
    public function testParseYamlWithCustomSeparator($string, $data, $content)
    {
        $frontMatter = new FrontMatter(null, '<!--', '-->');
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
    }

    /**
     * @dataProvider getSeparator
     */
    public function testDumpYamlWithCustomSeparator($string, $data, $content)
    {
        $frontMatter = new FrontMatter(null, '<!--', '-->');
        $document = new Document($content, $data);

        $this->assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getJson
     */
    public function testParseJson($string, $data, $content)
    {
        $frontMatter = new FrontMatter(new JsonProcessor());
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
    }

    /**
     * @dataProvider getJson
     */
    public function testDumpToJson($string, $data, $content)
    {
        $frontMatter = new FrontMatter(new JsonProcessor());

        $document = new Document($content, $data);
        $this->assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getYaml
     */
    public function testParseNeon($string, $data, $content)
    {
        $frontMatter = new FrontMatter(new NeonProcessor());
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
    }

    /**
     * @dataProvider getYaml
     */
    public function testDumpNeon($string, $data, $content)
    {
        $frontMatter = new FrontMatter(new NeonProcessor());
        $document = new Document($content, $data);

        $this->assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getToml
     */
    public function testParseToml($string, $data, $content)
    {
        $frontMatter = new FrontMatter(new TomlProcessor());
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
    }

    /**
     * @dataProvider getToml
     */
    public function testDumpToml($string, $data, $content)
    {
        $frontMatter = new FrontMatter(new TomlProcessor());
        $document = new Document($content, $data);

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
            ["---\n{\"foo\":\"bar\"}\n---\n", (object) ['foo' => 'bar'], ''],
            ["---\n{\"foo\":\"bar\"}\n---\ntext", (object) ['foo' => 'bar'], 'text'],
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
