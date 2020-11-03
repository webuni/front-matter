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

use PHPUnit\Framework\TestCase;
use Webuni\FrontMatter\Document;
use Webuni\FrontMatter\FrontMatter;
use Webuni\FrontMatter\Processor\JsonProcessor;
use Webuni\FrontMatter\Processor\JsonWithoutBracesProcessor;
use Webuni\FrontMatter\Processor\NeonProcessor;
use Webuni\FrontMatter\Processor\TomlProcessor;

class FrontMatterTest extends TestCase
{
    /**
     * @dataProvider getYaml
     */
    public function testYaml($string, $data, $content, bool $hasFrontMatter)
    {
        $frontMatter = new FrontMatter();
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
        $this->assertEquals($string, $frontMatter->dump($document));
        $this->assertSame($hasFrontMatter, $frontMatter->hasFrontMatter($string));
    }

    /**
     * @dataProvider getSeparator
     */
    public function testYamlWithCustomSeparator($string, $data, $content, bool $hasFrontMatter)
    {
        $frontMatter = new FrontMatter(null, '<!--', '-->');
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
        $this->assertEquals($string, $frontMatter->dump($document));
        $this->assertSame($hasFrontMatter, $frontMatter->hasFrontMatter($string));
    }

    /**
     * @dataProvider getJson
     */
    public function testJson($string, $data, $content, bool $hasFrontMatter)
    {
        $frontMatter = new FrontMatter(new JsonProcessor());
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
        $this->assertEquals($string, $frontMatter->dump($document));
        $this->assertSame($hasFrontMatter, $frontMatter->hasFrontMatter($string));
    }

    /**
     * @dataProvider getPlainJson
     */
    public function testPlainJson($string, $data, $content, bool $hasFrontMatter)
    {
        $frontMatter = new FrontMatter(new JsonWithoutBracesProcessor(), '{', '}');
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
        $this->assertEquals($string, $frontMatter->dump($document));
        $this->assertSame($hasFrontMatter, $frontMatter->hasFrontMatter($string));
    }

    /**
     * @dataProvider getYaml
     */
    public function testNeon($string, $data, $content, bool $hasFrontMatter)
    {
        $frontMatter = new FrontMatter(new NeonProcessor());
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
        $this->assertEquals($string, $frontMatter->dump($document));
        $this->assertSame($hasFrontMatter, $frontMatter->hasFrontMatter($string));
    }

    /**
     * @dataProvider getToml
     */
    public function testToml($string, $data, $content, bool $hasFrontMatter)
    {
        $frontMatter = new FrontMatter(new TomlProcessor());
        $document = $frontMatter->parse($string);

        $this->assertDocument($data, $content, $document);
        $this->assertSame($hasFrontMatter, $frontMatter->hasFrontMatter($string));

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Dump for Toml is not implemented.');

        $this->assertEquals($string, $frontMatter->dump($document));
    }

    public function getYaml()
    {
        return [
            ['foo', [], 'foo', false],
            ["---\nfoo: bar\n---\n", ['foo' => 'bar'], '', true],
            ["---\nfoo: bar\n---\ntext", ['foo' => 'bar'], 'text', true],
        ];
    }

    public function getSeparator()
    {
        return [
            ['foo', [], 'foo', false],
            ["<!--\nfoo: bar\n-->\n", ['foo' => 'bar'], '', true],
            ["<!--\nfoo: bar\n-->\ntext", ['foo' => 'bar'], 'text', true],
        ];
    }

    public function getJson()
    {
        return [
            ['foo', [], 'foo', false],
            ["---\n{\"foo\":\"bar\"}\n---\n", ['foo' => 'bar'], '', true],
            ["---\n{\"foo\":\"bar\"}\n---\ntext", ['foo' => 'bar'], 'text', true],
        ];
    }

    public function getPlainJson()
    {
        return [
            ['foo', [], 'foo', false],
            ["{\n\"foo\":\"bar\"\n}\n", ['foo' => 'bar'], '', true],
            ["{\n\"foo\":\"bar\"\n}\ntext", ['foo' => 'bar'], 'text', true],
        ];
    }

    public function getToml()
    {
        return [
            ['foo', [], 'foo', false],
            ["---\nfoo = 'bar'\n---\n", ['foo' => 'bar'], '', true],
            ["---\nfoo = 'bar'\n---\ntext", ['foo' => 'bar'], 'text', true],
        ];
    }

    private function assertDocument($data, $content, Document $document)
    {
        $this->assertEquals($data, $document->getData());
        $this->assertEquals($content, $document->getContent());
    }
}
