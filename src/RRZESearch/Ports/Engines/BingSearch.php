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

/**
 * Class BingSearch
 *
 * @package RRZE\RRZESearch\Ports\Engines
 */
class BingSearch implements Engine
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
        $context = stream_context_create(
            array(
                'http' => array(
                    'header' => "Ocp-Apim-Subscription-Key: $key\r\n",
                    'method' => 'GET'
                )
            )
        );

        $params = array(
            'q'            => $query,
            'customconfig' => 3022471055,
            'mkt'          => 'de-DE',
            'safesearch'   => 'Moderate',
            'count'        => 10,
            'offset'       => 10 * intval($startPage)
        );

        set_error_handler(
            create_function(
                '$severity, $message, $file, $line',
                'throw new ErrorException($message, $severity, $severity, $file, $line);'
            )
        );

        try {
            $result = file_get_contents(self::URI."?".http_build_query($params), false, $context);
        } catch (\Exception $exception) {
            echo 'HTTP request failed. Error was: '.$exception->getMessage().' PLEASE INFORM THE SYSTEM ADMINISTRATION';
        }


        /**
         * NOTICE that you should be returning a json string
         * ******************************************************************/
        return $result;
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

}