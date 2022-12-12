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

namespace Webuni\FrontMatter\Tests\Haml;

use MtHaml\Filter\FilterInterface;
use MtHaml\Node\Filter;
use MtHaml\NodeVisitor\RendererAbstract;
use PHPUnit\Framework\TestCase;
use Webuni\FrontMatter\Document;
use Webuni\FrontMatter\FrontMatterInterface;
use Webuni\FrontMatter\Haml\FrontMatterFilter;

final class FrontMatterFilterTest extends TestCase
{
    private $frontMatter;
    private $originalFilter;
    private $filter;

    protected function setUp(): void
    {
        $this->frontMatter = $this->createMock(FrontMatterInterface::class);
        $this->originalFilter = $this->createMock(FilterInterface::class);
        $this->filter = new FrontMatterFilter($this->frontMatter, $this->originalFilter);
    }

    public function testIsOptimizable(): void
    {
        $renderer = $this->createMock(RendererAbstract::class);
        $node = $this->createMock(Filter::class);

        $this->originalFilter->method('isOptimizable')->with($renderer, $node, $options = [])->willReturn(true);
        self::assertTrue($this->filter->isOptimizable($renderer, $node, $options));
    }

    public function testOptimize(): void
    {
        $renderer = $this->createMock(RendererAbstract::class);
        $node = $this->createMock(Filter::class);

        $this->originalFilter->method('optimize')->with($renderer, $node, $options = [])->willReturn('foo');
        self::assertEquals('foo', $this->filter->optimize($renderer, $node, $options));
    }

    public function testFilter(): void
    {
        $document = new Document('');

        $this->frontMatter->method('parse')->with($content = "---\n----\nstring")->willReturn($document);
        $this->originalFilter->method('filter')->with($document, $context = [], $options = [])->willReturn('string');

        self::assertEquals($document, $this->filter->filter($content, $context, $options));
    }
}
