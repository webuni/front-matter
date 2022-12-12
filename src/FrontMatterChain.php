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

namespace Webuni\FrontMatter;

final class FrontMatterChain implements FrontMatterInterface, FrontMatterExistsInterface
{
    /** @var FrontMatterInterface[] */
    private $adapters = [];

    public function __construct(iterable $adapters)
    {
        foreach ($adapters as $adapter) {
            if (!$adapter instanceof FrontMatterInterface) {
                throw new \InvalidArgumentException('Adapter should be instance of '.FrontMatterInterface::class);
            }

            $this->adapters[] = $adapter;
        }

        if (empty($this->adapters)) {
            throw new \InvalidArgumentException('It is necessary add at least one front matter adapter '.FrontMatterInterface::class);
        }
    }

    public function exists(string $source): bool
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter instanceof FrontMatterExistsInterface && $adapter->exists($source)) {
                return true;
            }
        }

        return false;
    }

    public function parse(string $source): Document
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter instanceof FrontMatterExistsInterface && $adapter->exists($source)) {
                return $adapter->parse($source);
            }
        }

        return new Document($source);
    }

    public function dump(Document $document): string
    {
        return $this->adapters[0]->dump($document);
    }
}
