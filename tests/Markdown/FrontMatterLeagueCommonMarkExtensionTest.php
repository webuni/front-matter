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

use League\CommonMark\DocParser;
use League\CommonMark\Environment as Environment1;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\HtmlRenderer as HtmlRenderer1;
use League\CommonMark\Parser\MarkdownParser;
use League\CommonMark\Renderer\HtmlRenderer;
use PHPUnit\Framework\TestCase;
use Webuni\FrontMatter\FrontMatter;
use Webuni\FrontMatter\Markdown\FrontMatterLeagueCommonMarkExtension;

final class FrontMatterLeagueCommonMarkExtensionTest extends TestCase
{
    private $environment;

    protected function setUp(): void
    {
        $frontMatter = FrontMatter::createYaml();
        if (class_exists(Environment1::class)) {
            $environment = Environment1::createCommonMarkEnvironment();
        } else {
            $environment = new Environment();
            $environment->addExtension(new CommonMarkCoreExtension());
        }
        $environment->addExtension(new FrontMatterLeagueCommonMarkExtension($frontMatter));

        $this->environment = $environment;
    }

    /**
     * @dataProvider getData
     */
    public function testConvert($markdown, $html, $data): void
    {
        $parser = class_exists(DocParser::class) ? new DocParser($this->environment) : new MarkdownParser($this->environment);
        $renderer = class_exists(HtmlRenderer1::class) ? new HtmlRenderer1($this->environment) : new HtmlRenderer($this->environment);

        $documentAST = $parser->parse($markdown);
        $documentData = is_array($documentAST->data) ? $documentAST->data : $documentAST->data->export();

        self::assertEquals([], array_diff_assoc($data, $documentData));
        self::assertEquals($html, method_exists($renderer, 'renderBlock') ? $renderer->renderBlock($documentAST) : $renderer->renderDocument($documentAST));
    }

    public function getData(): array
    {
        return [
            ["---\nfoo: bar\n---\nHead\n====\n", "<h1>Head</h1>\n", ['foo' => 'bar']],
        ];
    }
}
