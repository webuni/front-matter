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
use Webuni\FrontMatter\FrontMatterInterface;

class FrontMatterLoader implements \Twig_LoaderInterface, \Twig_ExistsLoaderInterface, \Twig_SourceContextLoaderInterface
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
        @trigger_error(sprintf('Calling "getSource" on "%s" is deprecated since Twig 1.27. Use getSourceContext() instead.', get_class($this)), E_USER_DEPRECATED);

        return $this->getSourceContext($name);
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
        if ($this->loader instanceof LoaderInterface || $this->loader instanceof \Twig_ExistsLoaderInterface) {
            return $this->loader->exists($name);
        }

        try {
            if ($this->loader instanceof \Twig_SourceContextLoaderInterface) {
                $this->loader->getSourceContext($name);
            } else {
                $this->loader->getSource($name);
            }

            return true;
        } catch (\Twig_Error_Loader $e) {
            return false;
        }
    }

    public function getSourceContext($name)
    {
        if ($this->loader instanceof LoaderInterface || $this->loader instanceof \Twig_SourceContextLoaderInterface) {
            $source = $this->loader->getSourceContext($name);
        } else {
            if ($this->loader instanceof \Twig_ExistsLoaderInterface && !$this->loader->exists($name)) {
                throw new \Twig_Error_Loader(sprintf('Template "%s" is not defined.', $name));
            }

            $source = new \Twig_Source($this->loader->getSource($name), $name);
        }

        return new \Twig_Source($this->parser->parse($source->getCode(), ['filename' => $name]), $source->getName(), $source->getPath());
    }
}
