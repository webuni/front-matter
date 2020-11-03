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
use League\CommonMark\Environment;
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

    public function testConvert()
    {
        $converter = new CommonMarkConverter([], $this->environment);
        $this->assertEquals("<h1>Head</h1>\n", $converter->convertToHtml("---\nfoo: bar\n---\nHead\n====\n"));
    }
}
