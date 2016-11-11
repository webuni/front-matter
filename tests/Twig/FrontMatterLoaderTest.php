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

use Webuni\FrontMatter\Document;
use Webuni\FrontMatter\FrontMatterInterface;
use Webuni\FrontMatter\Twig\FrontMatterLoader;

class FrontMatterLoaderTest extends \PHPUnit_Framework_TestCase
{
    private $frontMatter;
    private $originalLoader;
    private $loader;

    protected function setUp()
    {
        $this->frontMatter = $this->createMock(FrontMatterInterface::class);
        $this->originalLoader = $this->createMock(\Twig_LoaderInterface::class);

        $this->loader = new FrontMatterLoader($this->frontMatter, $this->originalLoader);
    }

    public function testGetCacheKey()
    {
        $this->originalLoader->method('getCacheKey')->with($name = 'name')->willReturn($name);
        $this->assertEquals($name, $this->loader->getCacheKey($name));
    }

    public function testIsFresh()
    {
        $this->originalLoader->method('isFresh')->with($name = 'name', $time = time())->willReturn(true);
        $this->assertTrue($this->loader->isFresh($name, $time));
    }

    public function testExists()
    {
        $this->originalLoader = $this->createMock([\Twig_LoaderInterface::class, \Twig_ExistsLoaderInterface::class]);
        $this->loader = new FrontMatterLoader($this->frontMatter, $this->originalLoader);

        $this->originalLoader->method('exists')->with($name = 'name')->willReturn(true);
        $this->assertTrue($this->loader->exists($name));
    }

    public function testGetSource()
    {
        $document = new Document('{{ foo }}', ['foo' => 'bar']);
        $this->originalLoader->method('getSource')->with($name = 'name')->willReturn($source = "---\nfoo: bar\n---\n{{ foo }}");
        $this->frontMatter->method('parse')->with($source, ['filename' => $name])->willReturn($document);

        $this->assertEquals($document, $this->loader->getSource($name));
    }
}
