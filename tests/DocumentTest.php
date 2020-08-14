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
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    public function testReturnContent()
    {
        $document = new Document($content = 'content');
        $this->assertEquals($content, $document->getContent());
    }

    public function testReturnData()
    {
        $document = new Document('content', $data = ['foo' => 'bar']);
        $this->assertEquals($data, $document->getData());
    }

    public function testReturnDataWithContent()
    {
        $document = new Document($content = 'content', ['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar', '__content' => $content], $document->getDataWithContent());
    }

    public function testSetContent()
    {
        $document = new Document('');
        $document->setContent($content = 'content');
        $this->assertEquals($content, $document->getContent());
    }

    public function testSetData()
    {
        $document = new Document('');
        $document->setData($data = ['foo' => 'bar']);
        $this->assertEquals($data, $document->getData());
    }
}
