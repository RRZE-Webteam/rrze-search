<?php

namespace RRZE\RRZESearch\Domain\Contract;

/**
 * Search Engine Interface
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Domain
 */
interface Engine
{
    /**
     * Query — Make your request and return its results
     *
     * @param string $query  Query string
     * @param string $key    API key
     * @param int $startPage Start page
     *
     * @return mixed Search results
     */
    public function query(string $query, string $key, int $startPage);

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