<?php

namespace RRZE\RRZESearch\Domain\Contract;
defined( 'ABSPATH' ) || exit;

/**
 * Contract that all RRZE Search engine adapters must implement.
 *
 * Defines the minimum surface for executing queries and exposing metadata so
 * engines can be rendered consistently across the admin UI and frontend.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Domain
 */
interface Engine
{
    /**
     * Executes the external search request and returns the raw results.
     *
     * @param string               $query      User-entered search string.
     * @param array<string, mixed> $args       Engine-specific configuration (keys, filters, etc.).
     * @param int                  $startPage  1-based page number for paginated APIs.
     *
     * @return mixed Search results as provided by the remote API implementation.
     */
    public function query(string $query, array $args, int $startPage);

    /**
     * Returns the human-readable engine name shown in the admin interface.
     *
     * @return string Translated engine name.
     */
    public static function getName(): string;

    /**
     * Returns the relative URL that serves the search results page.
     *
     * @return string Redirect path used when forwarding search requests.
     */
    public static function getRedirectLink(): string;

    /**
     * Returns the label displayed to users when selecting this engine.
     *
     * @return string Frontend-visible label.
     */
    public static function getLabel(): string;

    /**
     * Returns the label used when linking to the engine's policy or docs.
     *
     * @return string Description for the engine's external link.
     */
    public static function getLinkLabel(): string;
}
