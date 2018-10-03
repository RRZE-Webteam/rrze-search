<?php

namespace RRZE\RRZESearch\Infrastructure\Database;

/**
 * Database API
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class DatabaseApi
{
    /**
     * WordPress database instance
     *
     * @var \wpdb
     */
    protected $wpdb;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->wpdb =& $GLOBALS['wpdb'];
    }

    /**
     * Return a list of all post IDs and titles
     *
     * @return array|null|object
     * @todo Still needed?
     */
    public function get_posts()
    {
        return $this->wpdb->get_results(
            "SELECT `ID`,`post_title` FROM `wp_posts` WHERE `post_type` LIKE 'page'",
            ARRAY_A
        );
    }
}