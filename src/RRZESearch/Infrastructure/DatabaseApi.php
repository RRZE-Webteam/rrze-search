<?php

namespace RRZE\RRZESearch\Infrastructure;

class DatabaseApi
{
    private $wpdb;
//    public $table_name = '';

    public function __construct()
    {
        global $wpdb;
        $this->wpdb       = $wpdb;
    }

    public function get_posts()
    {
        return $this->wpdb->get_results(
            "SELECT `ID`,`post_title` FROM `wp_posts` WHERE `post_type` LIKE 'page'",
            ARRAY_A);
    }
}