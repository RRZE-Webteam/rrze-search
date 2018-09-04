<?php

namespace RRZE\RRZESearch\Domain\Contract;


interface Engine
{
    /**
     * Query - Make your request and return it's results
     *
     * @param string $query
     * @param string $key
     * @param int $startPage
     *
     * @return \stdClass
     */
    public function Query(string $query, string $key, int $startPage): \stdClass;

    /**
     * Return the name of this engine
     *
     * @return string
     */
    public static function getName(): string;
}