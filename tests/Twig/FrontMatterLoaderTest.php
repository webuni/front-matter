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
use Twig\Loader\ExistsLoaderInterface;
use Twig\Loader\LoaderInterface;
use Twig\Loader\SourceContextLoaderInterface;
use Twig\Source;
use Webuni\FrontMatter\Document;
use Webuni\FrontMatter\FrontMatterInterface;
use Webuni\FrontMatter\Twig\FrontMatterLoader;

class FrontMatterLoaderTest extends TestCase
{
    private $frontMatter;
    private $originalLoader;
    private $loader;

    protected function setUp(): void
    {
        $this->frontMatter = $this->createMock(FrontMatterInterface::class);
        $this->originalLoader = $this->createMock(LoaderInterface::class);

        $this->loader = new FrontMatterLoader($this->frontMatter, $this->originalLoader);
    }

    public function testGetCacheKey(): void
    {
        $this->originalLoader->method('getCacheKey')->with($name = 'name')->willReturn($name);
        $this->assertEquals($name, $this->loader->getCacheKey($name));
    }

    public function testIsFresh(): void
    {
        $this->originalLoader->method('isFresh')->with($name = 'name', $time = time())->willReturn(true);
        $this->assertTrue($this->loader->isFresh($name, $time));
    }

    public function testExists(): void
    {
        $loader = new FrontMatterLoader($this->frontMatter, $this->originalLoader);

        $this->originalLoader->method('exists')->with($name = 'name')->willReturn(true);
        $this->assertTrue($loader->exists($name));
    }

    public function testGetSourceContext(): void
    {
        $document = new Document('{{ foo }}', ['foo' => 'bar']);
        $name = 'name';
        $source = new Source("---\nfoo: bar\n---\n{{ foo }}", $name);

        $this->originalLoader
            ->method('getSourceContext')
            ->with($name)
            ->willReturn($source)
        ;
        $this->frontMatter->method('parse')->with($source->getCode())->willReturn($document);

        $this->assertEquals($document, $this->loader->getSourceContext($name)->getCode());
    }
}
