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

namespace Processor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webuni\FrontMatter\Processor\ProcessorDecorator;
use Webuni\FrontMatter\Processor\ProcessorInterface;

final class ProcessorDecoratorTest extends TestCase
{
    private MockObject $wrapped;
    private DummyProcessorDecorator $processor;

    protected function setUp(): void
    {
        $this->wrapped = $this->createMock(ProcessorInterface::class);
        $this->processor = new DummyProcessorDecorator($this->wrapped);
    }

    public function testParse(): void
    {
        $this->wrapped->expects($this->once())->method('parse')->with('{}');
        $this->processor->parse('{}');
    }

    public function testDump(): void
    {
        $this->wrapped->expects($this->once())->method('dump')->with(['foo' => 'bar']);
        $this->processor->dump(['foo' => 'bar']);
    }
}

final class DummyProcessorDecorator extends ProcessorDecorator
{
}
