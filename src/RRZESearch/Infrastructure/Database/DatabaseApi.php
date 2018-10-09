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

}