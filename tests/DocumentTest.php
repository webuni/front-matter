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

use PHPUnit\Framework\TestCase;
use Webuni\FrontMatter\Document;

final class DocumentTest extends TestCase
{
    public function testReturnContent(): void
    {
        $document = new Document($content = 'content');
        self::assertEquals($content, $document->getContent());
    }

    public function testToString(): void
    {
        $document = new Document($content = 'content');
        self::assertEquals($content, (string) $document);
    }

    public function testReturnData(): void
    {
        $document = new Document('content', $data = ['foo' => 'bar']);
        self::assertEquals($data, $document->getData());
    }

    public function testReturnDataWithContent(): void
    {
        $document = new Document($content = 'content', ['foo' => 'bar']);
        self::assertEquals(['foo' => 'bar', '__content' => $content], $document->getDataWithContent());
    }

    public function testSetContent(): void
    {
        $document = new Document('');
        $document->setContent($content = 'content');
        self::assertEquals($content, $document->getContent());
    }

    public function testSetData(): void
    {
        $document = new Document('');
        $document->setData($data = ['foo' => 'bar']);
        self::assertEquals($data, $document->getData());
    }
}
