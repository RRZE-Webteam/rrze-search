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

namespace RRZE\RRZESearch\Infrastructure\Engines\Foundations;
defined( 'ABSPATH' ) || exit;

/**
 * Google Custom Search Engine
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class GoogleSearch extends AbstractSearchEngine
{
    const NAME = 'Google Custom Search';

    const REDIRECT_LINK = '/rrze_search_page';

    // Constant Options fpr our Search
    // See also https://developers.google.com/custom-search/v1/cse/list
    const GCSE_OPTIONS = array(
	    'safe'  => 'active',	    
	    'filter'	=> 1,
	);

    /**
     * Query
     *
     * @param string $query
     * @param array $args
     * @param int $startPage
     *
     * @return mixed
     */
    public function query(string $query, array $args, int $startPage) {
        if (empty($args['cx']) || empty($args['key'])) {
            return [
                'error' => [
                    'code'    => 400,
                    'message' => __('Google search credentials are missing. Please provide both API key and CX identifier.', 'rrze-search'),
                ],
            ];
        }

        $requestQueryArgs = [
            'cx'    => $args['cx'],
            'key'   => $args['key'],
            'q'     => $query,
            'start' => max(1, (int)$startPage),
        ];

        if (!empty(self::GCSE_OPTIONS)) {
            $requestQueryArgs = array_merge($requestQueryArgs, self::GCSE_OPTIONS);
        }

        $requestUrl = add_query_arg($requestQueryArgs, 'https://www.googleapis.com/customsearch/v1');

        $response = wp_safe_remote_get($requestUrl, [
            'timeout'   => 10,
            'redirection' => 3,
            'headers'   => [
                'Accept' => 'application/json',
            ],
        ]);

        if (is_wp_error($response)) {
            return [
                'error' => [
                    'code'    => 500,
                    'message' => $response->get_error_message(),
                    'details' => [
                        'wp_error_code' => $response->get_error_code(),
                    ],
                ],
            ];
        }

        $statusCode = wp_remote_retrieve_response_code($response);
        $body       = wp_remote_retrieve_body($response);

        if ($statusCode >= 400) {
            $decodedError = json_decode($body, true);

            if (is_array($decodedError) && isset($decodedError['error'])) {
                return $decodedError;
            }

            return [
                'error' => [
                    'code'    => $statusCode,
                    'message' => $body ?: __('Google search request failed without a response body.', 'rrze-search'),
                ],
            ];
        }

        return $body;
    }

    public static function getName(): string
    {
        return __('Google FAU Search', 'rrze-search');
    }

    public static function getVariables(): array
    {
        return ['cx','key'];
    }
}
