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
use Webuni\FrontMatter\Processor\JsonProcessor;

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

        $document->shouldBeAnInstanceOf('Webuni\FrontMatter\Document');
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

    /**
     * @dataProvider get_separator_front_matter
     */
    public function it_should_parse_front_matter_with_custom_separators($string, $data, $content)
    {
        $this->beConstructedWith(null, '<!--', '-->');

        $document = $this->parse($string);

        $document->shouldBeAnInstanceOf('Webuni\FrontMatter\Document');
        $document->getData()->shouldBe($data);
        $document->getContent()->shouldBe($content);
    }

    /**
     * @dataProvider get_separator_front_matter
     */
    public function it_should_dump_front_matter_with_custom_separators($string, $data, $content)
    {
        $this->beConstructedWith(null, '<!--', '-->');

        $document = new Document($content, $data);
        $this->dump($document)->shouldBe($string);
    }

    /**
     * @dataProvider get_json_front_matter
     */
    public function it_should_parse_front_matter_from_json($string, $data, $content)
    {
        $this->beConstructedWith(new JsonProcessor());

        $document = $this->parse($string);

        $document->shouldBeAnInstanceOf('Webuni\FrontMatter\Document');
        $document->getData()->shouldBeLike($data);
        $document->getContent()->shouldBeLike($content);
    }

    /**
     * @dataProvider get_json_front_matter
     */
    public function it_should_dump_front_matter_to_json($string, $data, $content)
    {
        $this->beConstructedWith(new JsonProcessor());

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

    public function get_separator_front_matter()
    {
        return [
            ['foo', [], 'foo'],
            ["<!--\nfoo: bar\n-->\n", ['foo' => 'bar'], ''],
            ["<!--\nfoo: bar\n-->\ntext", ['foo' => 'bar'], 'text'],
        ];
    }

    public function get_json_front_matter()
    {
        return [
            ['foo', [], 'foo'],
            ["---\n{\"foo\":\"bar\"}\n---\n", (object) ['foo' => 'bar'], ''],
            ["---\n{\"foo\":\"bar\"}\n---\ntext", (object) ['foo' => 'bar'], 'text'],
        ];
    }
}
