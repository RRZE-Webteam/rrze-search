<?php
/***********************************************************************************
 *
 * RRZE-Webteam
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

namespace RRZE\RRZESearch\Infrastructure\Engines\Template;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Infrastructure\Engines\Foundations\AbstractSearchEngine;

/**
 * Template implementation showcasing how to build a multisearch adapter.
 *
 * Replace the placeholders with engine-specific configuration before using
 * this class in production.
 *
 * @package RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class SearchEngineClass extends AbstractSearchEngine
{
    /**
     * External engines render on the RRZE Search managed results page.
     */
    const REDIRECT_LINK = '';

    /**
     * Executes the external search request and returns the raw payload.
     *
     * @param string $query User-entered search query.
     * @param string $key API key or token required by the engine.
     * @param int    $startPage 1-based page number for paginated results.
     *
     * @return string|\WP_Error Response body on success or the encountered error.
     */
    public function query(string $query, string $key, int $startPage)
    {
        // STEP 1: Build the query parameters expected by the target API.
        $params = [
            'key'   => $key,
            'q'     => $query,
            'start' => max(1, $startPage),
            // Replace the placeholders below with engine-specific parameters as needed.
            'filter' => '{optional_filter}',
            'cx'     => '{custom_search_engine_id}',
        ];

        // STEP 2: Combine the base endpoint with the query arguments.
        $endpoint   = 'https://www.googleapis.com/customsearch/v1';
        $requestUrl = add_query_arg($params, $endpoint);

        // STEP 3: Dispatch the HTTP request via the WordPress HTTP API.
        $response = wp_safe_remote_get($requestUrl, [
            'headers' => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout' => 10,
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        return wp_remote_retrieve_body($response);
    }
}
