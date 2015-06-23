<?php

/*
 * This is part of the webuni/front-matter package.
 *
 * (c) Martin Hasoň <martin.hason@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Webuni\FrontMatter\Haml;

use MtHaml\Filter\FilterInterface;
use MtHaml\Node\Filter;
use MtHaml\NodeVisitor\RendererAbstract;
use PhpSpec\ObjectBehavior;
use Webuni\FrontMatter\Document;
use Webuni\FrontMatter\FrontMatterInterface;

class FrontMatterFilterSpec extends ObjectBehavior
{
    private $frontMatter;
    private $filter;

    public function it_is_initializable()
    {
        $this->shouldHaveType('Webuni\FrontMatter\Haml\FrontMatterFilter');
    }

    public function let(FrontMatterInterface $frontMatter, FilterInterface $filter)
    {
        $this->frontMatter = $frontMatter;
        $this->filter = $filter;

        $this->beConstructedWith($frontMatter, $filter);
    }

    public function it_should_call_isOptimizable_from_original_filter(RendererAbstract $renderer, Filter $node)
    {
        $this->filter->isOptimizable($renderer, $node, $options = [])->shouldBeCalled()->willReturn(true);
        $this->isOptimizable($renderer, $node, $options)->shouldBe(true);
    }

    public function it_should_call_optimize_from_original_filter(RendererAbstract $renderer, Filter $node)
    {
        $this->filter->optimize($renderer, $node, $options = [])->shouldBeCalled()->willReturn('foo');
        $this->optimize($renderer, $node, $options)->shouldBe('foo');
    }

    public function it_should_filter_content(Document $document)
    {
        $this->frontMatter->parse($content = "---\n----\nstring")->shouldBeCalled()->willReturn($document);
        $this->filter->filter($document, $context = [], $options = [])->shouldBeCalled()->willReturn('string');

        $this->filter($content, $context, $options)->shouldBe('string');
    }
}
