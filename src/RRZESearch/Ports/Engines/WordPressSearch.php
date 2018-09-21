<?php
/***********************************************************************************
 *
 * RRZE-Websteam
 * RRZE Search WordPress Plugin v1.0
 *
 * current file: SEARCH ENGINE Class Template
 *
 * /***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2018 tollwerk GmbH <info@tollwerk.de>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace RRZE\RRZESearch\Ports\Engines;

use RRZE\RRZESearch\Domain\Contract\Engine;
use WP_Query;

/**
 * Class WordPressSearch
 *
 * @package RRZE\RRZESearch\Ports\Engines
 */
class WordPressSearch implements Engine
{
    const NAME = 'WordPress Native Search';

    const URI = '/wp-json/wp/v2/posts';

    const LINK = '/rrze_search_page/';

    /**
     * Query - interface defined
     *
     * @param string $query
     * @param string $key
     * @param int $startPage
     *
     * @return mixed
     */
    public function Query(string $query, string $key, int $startPage)
    {
        $results = new WP_Query(array(
            'post_type'      => 'any',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            's'              => $query,
        ));

        return json_encode($results->posts);
    }

    /**
     * Return the name of this engine
     *
     * @return string
     */
    public static function getName(): string
    {
        return self::NAME;
    }

    /**
     * Return the link of this engine
     *
     * @return string
     */
    public static function getRedirectLink(): string
    {
        return self::LINK;
    }
}