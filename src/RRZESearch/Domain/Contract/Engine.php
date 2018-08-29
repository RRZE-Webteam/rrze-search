<?php

namespace RRZE\RRZESearch\Domain\Contract;


interface Engine
{
    /**
     * Query - Make your request and return it's results
     *
     * @param string $uri
     * @param string $key
     * @param int $query
     *
     * @return mixed
     */
    public function Query(string $uri, string $key, int $query);

    /**
     * Return the name of this engine
     *
     * @return mixed
     */
    public static function getName();
}