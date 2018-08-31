<?php

namespace RRZE\RRZESearch\Ports\Engines;

use RRZE\RRZESearch\Domain\Contract\Engine;

class GoogleSearch implements Engine
{
    const NAME = 'Google Custom Search';

    const URI = 'https://www.googleapis.com/customsearch/v1?cx=011945293402966620832:n0bvaqo6yl4&key={key}&q={query}';

    /**
     * Query
     *
     * @param string $query
     * @param string $key
     * @param int $startPage
     *
     * @return mixed
     */
    public function Query(string $query, string $key, int $startPage)
    {
        $params = array();

        /**
         * Append the StartPage Index and rebuild the URI
         */
        $uri        = self::URI.'&start='.$startPage;
        $parsed_url = parse_url($uri);
        $_uri       = $parsed_url['scheme'].'://'.$parsed_url['host'].$parsed_url['path'].'?';
        $_params    = explode('&', $parsed_url['query']);

        /**
         * Replace {key}, {query} placeholder
         */
        foreach ($_params as $param) {
            $split = explode('=', $param);
            switch ($split[1]) {
                case '{key}':
                    $params[$split[0]] = rawurlencode($key);
                    break;
                case '{query}':
                    $params[$split[0]] = rawurlencode($query);
                    break;
                default:
                    $params[$split[0]] = $split[1];
            }
        }
        /**
         * Rejoin the parameters to URI
         */
        $_uri .= http_build_query($params);

        /**
         * Curl Headers
         */
        $headers = array(
            'Content-length: 0',
            'Content-type: application/json'
        );

        /**
         * Curl Options Array
         */
        $curlOptions = array(
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_HEADER         => false,
            CURLOPT_URL            => urldecode($_uri),
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HEADER         => 0,
            CURLOPT_FOLLOWLOCATION => 1
        );

        /**
         * Try to make query request
         */
        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);

        /**
         * Finalize query request
         */
        $results = curl_exec($curl);
        curl_close($curl);

        return $results;
    }

    /**
     * Return the name of this engine
     *
     * @return mixed
     */
    public static function getName()
    {
        return self::NAME;
    }
}