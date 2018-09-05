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

/** Use the following NAMESPACE */

namespace RRZE\RRZESearch\Ports\Engines;

/** Implement the Contract Interface */

use RRZE\RRZESearch\Domain\Contract\Engine;

/**
 * TODO: refactor class name
 *
 * Class DuckGoSearch
 */
class DuckGoSearch implements Engine
{
    const NAME = 'Microsoft Bing';

    const URI = 'https://api.cognitive.microsoft.com/bingcustomsearch/v7.0/search';
//    const URI = 'https://api.cognitive.microsoft.com/bing/v7.0/search'; // Documentation Example

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
        echo 'working on it<br>';

        $context = stream_context_create(
            array(
                'http' => array(
                    'header' => "Ocp-Apim-Subscription-Key: $key\r\n",
                    'method' => 'GET'
                )
            )
        );

        $result  = file_get_contents(self::URI."?q=".urlencode($query), false, $context);
        $headers = array();
        foreach ($http_response_header as $k => $v) {
            $h = explode(":", $v, 2);
            if (isset($h[1])) {
                if (preg_match("/^BingAPIs-/", $h[0]) || preg_match("/^X-MSEdge-/", $h[0])) {
                    $headers[trim($h[0])] = trim($h[1]);
                }
            }
        }



        /**
         * STEP 1 - Build the query
         */
//        $params = array(
//            'q'            => $query,
//            'customconfig' => 3022471055,
//            'mkt'          => 'de-DE',
//            'safesearch'   => 'Moderate',
//            'count'        => 10,
//            'offset'       => 0
//        );

        /**
         * STEP 2 - Build URL
         */
//        $_uri = self::URI;
//        $_uri .= '?'.http_build_query($params);

        /**
         * STEP 3 - Curl headers array
         */
//        $curlHeaders = array(
//            'Content-length: 0',
//            'Content-type: application/json',
//        );

        /**
         * STEP 4 - Curl options array
         */
//        $curlOptions = array(
//            CURLOPT_HTTPHEADER => $curlHeaders,
//            CURLOPT_URL        => urldecode($_uri),
//        );

        /**
         * STEP 5 - Make the request
         */
//        $curl = curl_init();
//        curl_setopt_array($curl, $curlOptions);

        /**
         * STEP 6 - Finalize query request
         */
//        $results = curl_exec($curl);
//        curl_close($curl);

        /**
         * NOTICE that you should be returning a json string as \StdClass
         * ******************************************************************/
        echo '<pre>'.PHP_EOL;
        echo '<h1>OUTPUT</h1>';
        echo '</pre>';

        return array($headers, $result);
    }

    /**
     * Return the name of this engine
     *
     * @return string
     */
    public
    static function getName(): string
    {
        return self::NAME;
    }
}