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
     * @param string $query
     * @param array $args
     * @param int $startPage
     *
     * @return mixed Search results
     */
    public function query(string $query, array $args, int $startPage);

    /**
     * Return the name of this engine
     *
     * @return string
     */
    public static function getName(): string;

    /**
     * Return the redirect link
     *
     * @return string
     */
    public static function getRedirectLink(): string;

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