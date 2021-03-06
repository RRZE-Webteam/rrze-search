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
        $_uri = sprintf('https://www.googleapis.com/customsearch/v1?cx=%s&key=%s&q=%s&start=%s',
            $args['cx'],
            $args['key'],
            rawurlencode($query),
            $startPage
        );

        // cURL headers
        $headers = [
            'Content-length: 0',
            'Content-type: application/json'
        ];

	if ((self::GCSE_OPTIONS) && (!empty(self::GCSE_OPTIONS))) {
	    $addquery = http_build_query(self::GCSE_OPTIONS);
	    $_uri .= '&'.$addquery;
	}
	
	
        // cURL options
        $curlOptions = array(
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_HEADER         => false,
            CURLOPT_URL            => $_uri,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HEADER         => 0,
            CURLOPT_FOLLOWLOCATION => 1
        );

        // Try to make query request
        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);
        // Finalize query request
        $results = curl_exec($curl);
        curl_close($curl);

        return $results;
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