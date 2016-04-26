<?php

/*
 * This is part of the webuni/front-matter package.
 *
 * (c) Martin Hasoň <martin.hason@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Webuni\FrontMatter\Twig;

use PhpSpec\ObjectBehavior;
use Webuni\FrontMatter\Document;
use Webuni\FrontMatter\FrontMatterInterface;

/**
 * @mixin \Webuni\FrontMatter\Twig\FrontMatterLoader
 */
class FrontMatterLoaderSpec extends ObjectBehavior
{
    private $frontMatter;
    private $loader;

    public function it_is_initializable()
    {
        $this->shouldHaveType('Webuni\FrontMatter\Twig\FrontMatterLoader');
    }

    public function let(FrontMatterInterface $frontMatter, \Twig_LoaderInterface $loader)
    {
        $this->frontMatter = $frontMatter;
        $this->loader = $loader;

        $this->beConstructedWith($frontMatter, $loader);
    }

    public function it_should_call_getCacheKey_from_original_loader()
    {
        $this->loader->getCacheKey($name = 'name')->shouldBeCalled()->willReturn($name);
        $this->getCacheKey($name)->shouldBe($name);
    }

    public function it_should_call_isFresh_from_original_loader()
    {
        $this->loader->isFresh($name = 'name', $time = time())->shouldBeCalled()->willReturn(true);
        $this->isFresh($name, $time)->shouldBe(true);
    }

    public function it_should_call_exists_from_original_loader(\Twig_ExistsLoaderInterface $loader)
    {
        $this->beConstructedWith($this->frontMatter, $loader);

        $loader->exists($name = 'name')->shouldBeCalled()->willReturn(true);
        $this->exists($name)->shouldBe(true);
    }

    public function it_should_getSource_with_front_matter(Document $document)
    {
        $this->loader->getSource($name = 'name')->shouldBeCalled()->willReturn($source = "---\nfoo: bar\n---\n{{ foo }}");
        $this->frontMatter->parse($source, ['filename' => $name])->shouldBeCalled()->willReturn($document);

        $this->getSource($name)->shouldBe($document);
    }
}
