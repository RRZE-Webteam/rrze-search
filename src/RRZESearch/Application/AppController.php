<?php

namespace RRZE\RRZESearch\Application;

class AppController
{
    public $plugin_path;
    public $plugin_url;
    public $plugin;

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 3));
        $this->plugin = plugin_basename(dirname(__FILE__, 4)) . '/rrze-search.php';

//        echo '<pre>';
//        print_r($this);
//        echo '</pre>';
    }

    public function activated($key)
    {
        $option = get_option('rrze_search_settings');
        return isset($option[$key]) ? $option[$key] : false;
    }
}