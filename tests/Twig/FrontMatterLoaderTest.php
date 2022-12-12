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
use Webuni\FrontMatter\FrontMatter;
use Webuni\FrontMatter\Twig\FrontMatterLoader;

class FrontMatterLoaderTest extends TestCase
{
    private $frontMatter;
    private $originalLoader;
    private $loader;

    protected function setUp(): void
    {
        $this->frontMatter = FrontMatter::createYaml();
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

    /**
     * @dataProvider getSource
     */
    public function testGetSourceContext(string $source, string $content, array $data): void
    {
        $name = 'name';
        $source = new Source($source, $name);

        $this->originalLoader
            ->method('getSourceContext')
            ->with($name)
            ->willReturn($source)
        ;
        $document = $this->frontMatter->parse($source->getCode());
        self::assertEquals($data, $document->getData());
        self::assertEquals($content, $this->loader->getSourceContext($name)->getCode());
    }

    public function getSource(): array
    {
        return [
            ["{{ foo }}", '{{ foo }}', []],
            ["---\nfoo: bar\n---\n{{ foo }}", "\n\n\n\n{{ foo }}", ['foo' => 'bar']],
        ];
    }
}
