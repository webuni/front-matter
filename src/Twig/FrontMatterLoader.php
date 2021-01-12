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

namespace Webuni\FrontMatter\Twig;

use Twig\Loader\LoaderInterface;
use Twig\Source;
use Webuni\FrontMatter\FrontMatterInterface;

class FrontMatterLoader implements LoaderInterface
{
    /** @var LoaderInterface */
    private $loader;

    /** @var FrontMatterInterface */
    private $parser;

    public function __construct(FrontMatterInterface $parser, LoaderInterface $loader)
    {
        $this->loader = $loader;
        $this->parser = $parser;
    }

    public function getCacheKey(string $name): string
    {
        return $this->loader->getCacheKey($name);
    }

    public function isFresh(string $name, int $time): bool
    {
        return $this->loader->isFresh($name, $time);
    }

    public function exists(string $name): bool
    {
        return $this->loader->exists($name);
    }

    public function getSourceContext(string $name): Source
    {
        $source = $this->loader->getSourceContext($name);
        $code = $source->getCode();
        $document = $this->parser->parse($code);
        $content = $document->getContent();

        $lines = $code === $content ? 1 : substr_count($code, "\n", 0, - strlen($content)) + 1;
        if ($lines > 1) {
            $content = "{% line $lines %}$content";
        }

        return new Source($content, $source->getName(), $source->getPath());
    }
}
