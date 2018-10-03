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

namespace RRZE\RRZESearch\Infrastructure\Engines\Foundations;

use WP_Query;

/**
 * WordPress Search Engine
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Ports
 */
class WordPressSearch extends AbstractSearchEngine
{
    /**
     * Request URI
     *
     * @var string
     * @todo For what?
     */
    const URI = '/wp-json/wp/v2/posts';

    /**
     * Query - interface defined
     *
     * @param string $query
     * @param string $key
     * @param int $startPage
     *
     * @return mixed
     */
    public function query(string $query, string $key, int $startPage)
    {
        $results = new WP_Query([
            'post_type'      => 'any',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            's'              => $query,
        ]);

        return json_encode($results->posts);
    }
}