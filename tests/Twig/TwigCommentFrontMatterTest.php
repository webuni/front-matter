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

/**
 * @internal
 */
final class TwigCommentFrontMatterTest extends TestCase
{
    public function testCreate(): void
    {
        $frontMatter = TwigCommentFrontMatter::create();
        self::assertInstanceOf(FrontMatter::class, $frontMatter);

        $document = $frontMatter->parse("{#---\nfoo: bar\n---#}\nContent\n");
        self::assertEquals(['foo' => 'bar'], $document->getData());
        self::assertEquals("Content\n", $document->getContent());
    }
}
