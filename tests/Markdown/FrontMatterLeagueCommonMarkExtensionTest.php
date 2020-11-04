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

namespace Webuni\FrontMatter\Tests\Markdown;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use PHPUnit\Framework\TestCase;
use Webuni\FrontMatter\FrontMatter;
use Webuni\FrontMatter\Markdown\FrontMatterLeagueCommonMarkExtension;

class FrontMatterLeagueCommonMarkExtensionTest extends TestCase
{
    private $environment;

    protected function setUp(): void
    {
        $this->environment = $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new FrontMatterLeagueCommonMarkExtension(FrontMatter::createYaml()));
    }

    /**
     * @dataProvider getData
     */
    public function testConvert($markdown, $html, $data): void
    {
        $parser = new DocParser($this->environment);
        $renderer = new HtmlRenderer($this->environment);

        $documentAST = $parser->parse($markdown);

        $this->assertEquals($data, $documentAST->data);
        $this->assertEquals($html, $renderer->renderBlock($documentAST));
    }

    public function getData(): array
    {
        return [
            ["---\nfoo: bar\n---\nHead\n====\n", "<h1>Head</h1>\n", ['foo' => 'bar']],
        ];
    }
}
