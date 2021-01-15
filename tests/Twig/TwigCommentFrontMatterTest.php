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
use Webuni\FrontMatter\FrontMatter;
use Webuni\FrontMatter\Twig\TwigCommentFrontMatter;

class TwigCommentFrontMatterTest extends TestCase
{
    public function testCreate()
    {
        $frontMatter = TwigCommentFrontMatter::create();
        $this->assertInstanceOf(FrontMatter::class, $frontMatter);

        $document = $frontMatter->parse("{#---\nfoo: bar\n---#}\nContent\n");
        $this->assertEquals(['foo' => 'bar'], $document->getData());
        $this->assertEquals("Content\n", $document->getContent());
    }
}
