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

interface FrontMatterExistsInterface
{
    /**
     * Check if a source has a front matter.
     *
     * @param string $source The source
     *
     * @return bool If the source has a front matter
     */
    public function exists(string $source): bool;
}
