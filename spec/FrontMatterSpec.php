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
use Webuni\FrontMatter\Document;

class FrontMatterSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Webuni\FrontMatter\FrontMatter');
    }

    /**
     * @dataProvider get_yaml_front_matter
     */
    public function it_should_parse_front_matter_from_yaml($string, $data, $content)
    {
        $document = $this->parse($string);

        $document->shouldBeAnInstanceOf(Document::class);
        $document->getData()->shouldBe($data);
        $document->getContent()->shouldBe($content);
    }

    /**
     * @dataProvider get_yaml_front_matter
     */
    public function it_should_dump_front_matter_to_yaml($string, $data, $content)
    {
        $document = new Document($content, $data);
        $this->dump($document)->shouldBe($string);
    }

    public function get_yaml_front_matter()
    {
        return [
            ['foo', [], 'foo'],
            ["---\nfoo: bar\n---\n", ['foo' => 'bar'], ''],
            ["---\nfoo: bar\n---\ntext", ['foo' => 'bar'], 'text'],
        ];
    }
}
