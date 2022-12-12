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

namespace Pug;

use PHPUnit\Framework\TestCase;
use Webuni\FrontMatter\FrontMatter;
use Webuni\FrontMatter\Pug\PugCommentFrontMatter;

final class PugCommentFrontMatterTest extends TestCase
{
    public function testCreate(): void
    {
        $frontMatter = PugCommentFrontMatter::create();
        self::assertInstanceOf(FrontMatter::class, $frontMatter);

        $document = $frontMatter->parse("//-\n  foo: bar\n  items:\n    - item\n\nContent\n");
        self::assertEquals(['foo' => 'bar', 'items' => ['item']], $document->getData());
        self::assertEquals("Content\n", $document->getContent());
    }
}
