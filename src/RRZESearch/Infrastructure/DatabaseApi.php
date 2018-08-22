<?php

namespace RRZE\RRZESearch\Infrastructure;

class DatabaseApi
{
    private $wpdb;
    public $table_name = '';

    public function __construct()
    {
        global $wpdb;
        $this->wpdb       = $wpdb;
        $this->table_name = $wpdb->prefix.'rrze_search_resources';
    }

    public function register()
    {
        if ($this->table_name != '') {
            $this->create_db_table();
//            $this->set_resource('')
        }

        /*
         * TODO add additional tasks!
         */
    }

    public function create_db_table()
    {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE $this->table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  resource_name tinytext NOT NULL,
  resource_uri text NOT NULL,
  resource_key text NOT NULL,
  PRIMARY KEY  (id)
) $charset_collate;";

        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function get_search_resources()
    {
        return $this->wpdb->get_results("SELECT * FROM `wp_rrze_search_resources`", ARRAY_A);
    }

    public function get_search_resource($id)
    {
        return $this->wpdb->get_results("SELECT * FROM `wp_rrze_search_resources` WHERE `id` LIKE ".$id, OBJECT);
    }

    public function set_search_resource($args)
    {
        $data         = $args;
        $data['time'] = current_time('mysql', 1);
        $this->wpdb->insert('wp_rrze_search_resources', $data);
        wp_redirect('options-general.php?page=rrze_search');
    }

    public function remove_search_resource($id)
    {
        $this->wpdb->delete('wp_rrze_search_resources', array('id' => $id));
        wp_redirect('options-general.php?page=rrze_search');
    }


    public function get_posts()
    {
        return $this->wpdb->get_results("SELECT `ID`,`post_title` FROM `wp_posts` WHERE `post_type` LIKE 'page'",
            ARRAY_A);
    }
}