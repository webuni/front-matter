<?php

/*
 * This is part of the webuni/front-matter package.
 *
 * (c) Martin Hasoň <martin.hason@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Webuni\FrontMatter;

use PhpSpec\ObjectBehavior;

class DocumentSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith('');
        $this->shouldHaveType('Webuni\FrontMatter\Document');
    }

    public function it_should_return_content()
    {
        $this->beConstructedWith($content = 'content');
        $this->getContent()->shouldReturn($content);
    }

    public function it_should_return_data()
    {
        $this->beConstructedWith('content', $data = ['foo' => 'bar']);
        $this->getData()->shouldReturn($data);
    }

    public function it_should_return_data_with_content()
    {
        $this->beConstructedWith($content = 'content', $data = ['foo' => 'bar']);
        $this->getDataWithContent()->shouldReturn(array_merge($data, ['__content' => $content]));
    }

    public function it_should_set_content()
    {
        $this->beConstructedWith('');
        $this->setContent($content = 'content');
        $this->getContent()->shouldReturn($content);
    }

    public function it_should_set_data()
    {
        $this->beConstructedWith('');
        $this->setData($data = ['foo' => 'bar']);
        $this->getData()->shouldReturn($data);
    }
}
