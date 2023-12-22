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

use ArrayObject;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Webuni\FrontMatter\FrontMatter;
use Webuni\FrontMatter\FrontMatterChain;
use Webuni\FrontMatter\FrontMatterInterface;

final class FrontMatterChainTest extends TestCase
{
    private FrontMatterChain $chain;

    protected function setUp(): void
    {
        $this->chain = new FrontMatterChain([
            FrontMatter::createYaml(),
            FrontMatter::createJson(),
        ]);
    }

    public function testEmptyAdapters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('It is necessary add at least one front matter adapter '.FrontMatterInterface::class);

        new FrontMatterChain([]);
    }

    public function testInvalidArrayOfAdapters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Adapter should be instance of '.FrontMatterInterface::class);

        new FrontMatterChain([new ArrayObject()]);
    }

    /**
     * @dataProvider getFrontMatter
     */
    public function testExists(string $source): void
    {
        self::assertTrue($this->chain->exists($source));
    }

    /**
     * @dataProvider getFrontMatter
     */
    public function testParse(string $source, array $data, string $content): void
    {
        $document = $this->chain->parse($source);
        self::assertEquals($data, $document->getData());
        self::assertEquals($content, $document->getContent());
    }

    public static function getFrontMatter(): array
    {
        return [
            ["---\nfoo: bar\n---\nContent", ['foo' => 'bar'], 'Content'],
            ["{\n  \"foo\": \"bar\"\n}\nContent", ['foo' => 'bar'], 'Content'],
        ];
    }
}