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

namespace RRZE\RRZESearch\Infrastructure\Engines\Foundations;

/** Implement the Contract Interface */

use RRZE\RRZESearch\Domain\Contract\Engine;

/**
 * TODO: refactor class name
 *
 * Class SearchEngine
 */
//class SearchEngineClass implements Engine
//{
//    /**
//     * Query - interface defined
//     *
//     * @param string $query
//     * @param string $key
//     * @param int $startPage
//     *
//     * @return mixed
//     */
//    public function Query(string $query, string $key, int $startPage)
//    {
//        /**
//         * STEP 1 - Build the query
//         */
//        $params = array(
//            'key'    => '{key}',
//            'query'  => '{query}',
//            'filter' => '{filter}',
//        );
//
//        /**
//         * STEP 2 - Build URL
//         */
//        $_uri = self::URI;
//        $_uri .= '?'.http_build_query($params);
//
//        /**
//         * STEP 3 - Curl headers array
//         */
//        $curlHeaders = array(
//            'Content-length: 0',
//            'Content-type: application/json'
//        );
//
//        /**
//         * STEP 4 - Curl options array
//         */
//        $curlOptions = array(
//            CURLOPT_HTTPHEADER     => $curlHeaders ,
//            CURLOPT_URL            => urldecode($_uri),
//        );
//
//        /**
//         * STEP 5 - Make the request
//         */
//        $curl = curl_init();
//        curl_setopt_array($curl, $curlOptions);
//
//        /**
//         * STEP 6 - Finalize query request
//         */
//        $results = curl_exec($curl);
//        curl_close($curl);
//
//        /**
//         * NOTICE that you should be returning a json string as \StdClass
//         * ******************************************************************/
//        return $results;
//    }
//
//    /**
//     * Return the name of this engine
//     *
//     * @return string
//     */
//    public static function getName(): string
//    {
//        return self::NAME;
//    }
//
//    /**
//     * Return the label of this engine
//     *
//     * @return string
//     */
//    public static function getLabel(): string
//    {
//        return self::LABEL;
//    }
//
//    /**
//     * Return the external link of this engine
//     *
//     * @return string
//     */
//    public static function getLinkLabel(): string
//    {
//        return self::LINK_LABEL;
//    }
//}