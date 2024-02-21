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

final class FrontMatterTest extends TestCase
{
    /**
     * @dataProvider getYaml
     */
    public function testYaml(string $string, array $data, string $content, bool $hasFrontMatter): void
    {
        $frontMatter = new FrontMatter();
        $document = $frontMatter->parse($string);

        self::assertSame($hasFrontMatter, $frontMatter->exists($string));
        self::assertDocument($data, $content, $document);
        self::assertEquals($string, $frontMatter->dump($document));
    }

    public function testIndentedYaml(): void
    {
        $frontMatter = new FrontMatter();
        $document = $frontMatter->parse("---\n\n\n  \n  foo: bar\n  text: |\n    text\n\n---\nContent");

        self::assertDocument(['foo' => 'bar', 'text' => 'text'], 'Content', $document);
    }

    public function testEmptyYaml(): void
    {
        $frontMatter = new FrontMatter();
        $document = $frontMatter->parse("---\n\n\n  \n    \n\n---\nContent");

        self::assertDocument([], 'Content', $document);
    }

    /**
     * @dataProvider getSeparator
     */
    public function testYamlWithCustomSeparator(
        string $string,
        array $data,
        string $content,
        bool $hasFrontMatter
    ): void
    {
        $frontMatter = new FrontMatter(null, '<!--', '-->');
        $document = $frontMatter->parse($string);

        self::assertSame($hasFrontMatter, $frontMatter->exists($string));
        self::assertDocument($data, $content, $document);
        self::assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getJson
     */
    public function testJson(string $string, array $data, string $content, bool $hasFrontMatter): void
    {
        $frontMatter = new FrontMatter(new JsonProcessor());
        $document = $frontMatter->parse($string);

        self::assertSame($hasFrontMatter, $frontMatter->exists($string));
        self::assertDocument($data, $content, $document);
        self::assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getPlainJson
     */
    public function testPlainJson(string $string, array $data, string $content, bool $hasFrontMatter): void
    {
        $frontMatter = new FrontMatter(new JsonWithoutBracesProcessor(), '{', '}');
        $document = $frontMatter->parse($string);

        self::assertSame($hasFrontMatter, $frontMatter->exists($string));
        self::assertDocument($data, $content, $document);
        self::assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getYaml
     */
    public function testNeon(string $string, array $data, string $content, bool $hasFrontMatter): void
    {
        $frontMatter = new FrontMatter(new NeonProcessor());
        $document = $frontMatter->parse($string);

        self::assertSame($hasFrontMatter, $frontMatter->exists($string));
        self::assertDocument($data, $content, $document);
        self::assertEquals($string, $frontMatter->dump($document));
    }

    /**
     * @dataProvider getToml
     */
    public function testToml(string $string, array $data, string $content, bool $hasFrontMatter): void
    {
        $frontMatter = new FrontMatter(new TomlProcessor());
        $document = $frontMatter->parse($string);

        self::assertSame($hasFrontMatter, $frontMatter->exists($string));
        self::assertDocument($data, $content, $document);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Dump for Toml is not implemented.');

        self::assertEquals($string, $frontMatter->dump($document));
    }

    public static function getYaml(): array
    {
        return [
            ['foo', [], 'foo', false],
            ["---\nfoo: bar\n---\n", ['foo' => 'bar'], '', true],
            ["---\nfoo: bar\n---\ntext", ['foo' => 'bar'], 'text', true],
        ];
    }

    public static function getSeparator(): array
    {
        return [
            ['foo', [], 'foo', false],
            ["<!--\nfoo: bar\n-->\n", ['foo' => 'bar'], '', true],
            ["<!--\nfoo: bar\n-->\ntext", ['foo' => 'bar'], 'text', true],
        ];
    }

    public static function getJson(): array
    {
        return [
            ['foo', [], 'foo', false],
            ["---\n{\"foo\":\"bar\"}\n---\n", ['foo' => 'bar'], '', true],
            ["---\n{\"foo\":\"bar\"}\n---\ntext", ['foo' => 'bar'], 'text', true],
        ];
    }

    public static function getPlainJson(): array
    {
        return [
            ['foo', [], 'foo', false],
            ["{\n\"foo\":\"bar\"\n}\n", ['foo' => 'bar'], '', true],
            ["{\n\"foo\":\"bar\"\n}\ntext", ['foo' => 'bar'], 'text', true],
        ];
    }

    public static function getToml(): array
    {
        return [
            ['foo', [], 'foo', false],
            ["---\nfoo = 'bar'\n---\n", ['foo' => 'bar'], '', true],
            ["---\nfoo = 'bar'\n---\ntext", ['foo' => 'bar'], 'text', true],
        ];
    }

    private static function assertDocument(array $data, string $content, Document $document): void
    {
        self::assertEquals($data, $document->getData());
        self::assertEquals($content, $document->getContent());
    }
}
