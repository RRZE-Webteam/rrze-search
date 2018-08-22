<?php

namespace RRZE\RRZESearch\Domain\Contract;


interface Engine
{
    /**
     * Return the name of this engine
     *
     * @return mixed
     */
    public static function getName();

    /**
     * Run a search query
     *
     * @param $uri
     * @param $key
     * @param $query
     *
     * @return mixed
     */
    public function Query($uri, $key, $query);
}