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
use League\CommonMark\Parser\MarkdownParser;
use League\CommonMark\Renderer\HtmlRenderer;
use PHPUnit\Framework\TestCase;
use Webuni\FrontMatter\FrontMatter;
use Webuni\FrontMatter\Markdown\FrontMatterLeagueCommonMarkExtension;

final class FrontMatterLeagueCommonMarkExtensionTest extends TestCase
{
    private Environment $environment;

    protected function setUp(): void
    {
        $frontMatter = FrontMatter::createYaml();
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new FrontMatterLeagueCommonMarkExtension($frontMatter));

        $this->environment = $environment;
    }

    /**
     * @dataProvider getData
     */
    public function testConvert(string $markdown, string $html, array $data): void
    {
        $parser = new MarkdownParser($this->environment);
        $renderer = new HtmlRenderer($this->environment);

        $documentAST = $parser->parse($markdown);
        $documentData = $documentAST->data->export();

        self::assertEquals([], array_diff_assoc($data, $documentData));
        self::assertEquals($html, $renderer->renderDocument($documentAST));
    }

    public static function getData(): array
    {
        return [
            ["---\nfoo: bar\n---\nHead\n====\n", "<h1>Head</h1>\n", ['foo' => 'bar']],
        ];
    }
}
