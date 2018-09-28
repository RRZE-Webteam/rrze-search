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
     */
    public function Query(string $query, string $key, int $startPage);

    /**
     * Return the name of this engine
     *
     * @return string
     */
    public static function getName(): string;

    /**
     * Return the label for this engine
     *
     * @return string
     */
    public static function getLabel(): string;

    /**
     * Return the label for this engine's external link
     *
     * @return string
     */
    public static function getLinkLabel(): string;
}