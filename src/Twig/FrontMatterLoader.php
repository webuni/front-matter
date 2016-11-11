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

use Webuni\FrontMatter\FrontMatterInterface;

class FrontMatterLoader implements \Twig_LoaderInterface, \Twig_ExistsLoaderInterface
{
    private $loader;
    private $parser;

    public function __construct(FrontMatterInterface $parser, \Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
        $this->parser = $parser;
    }

    public function getSource($name)
    {
        $source = $this->loader->getSource($name);

        return $this->parser->parse($source, ['filename' => $name]);
    }

    public function getCacheKey($name)
    {
        return $this->loader->getCacheKey($name);
    }

    public function isFresh($name, $time)
    {
        return $this->loader->isFresh($name, $time);
    }

    public function exists($name)
    {
        if ($this->loader instanceof \Twig_ExistsLoaderInterface) {
            return $this->loader->exists($name);
        } else {
            try {
                $this->loader->getSource($name);

                return true;
            } catch (\Twig_Error_Loader $e) {
                return false;
            }
        }
    }
}
