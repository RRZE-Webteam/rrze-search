<?php

namespace RRZE\RRZESearch\Ports\Engines;

use RRZE\RRZESearch\Domain\Contract\Engine;

class GoogleSearch implements Engine
{
    /**
     * Search engine name
     *
     * @var string
     */
    const NAME = 'Google Custom Search';

    const URI = 'https://www.googleapis.com/customsearch/v1?cx=011945293402966620832:n0bvaqo6yl4&key={key}&q={query}';

    public function Query($query, $key, $startPage)
    {
        $uri = self::URI.'&start='.$startPage;
        /**
         * Will only run if the URI is not empty string.
         */
        $parsed_url = parse_url($uri);
        $url        = $parsed_url['scheme'].'://'.$parsed_url['host'].$parsed_url['path'].'?';

        $params  = array();
        $_params = explode('&', $parsed_url['query']);
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
                    $params[$split[0]] = urlencode($split[1]);
            }
        }
        $i = 1;
        foreach ($params as $key => $value) {
            $url .= $key.'='.$value;
            if ($i < count($params)) {
                $url .= '&';
            }
            $i++;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result    = curl_exec($curl);

//            header('Content-type: application/json');
        curl_close($curl);

        /**
         * Add Configuration for each results schema
         */
        return $result;
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