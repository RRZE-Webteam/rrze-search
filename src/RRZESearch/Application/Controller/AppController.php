<?php

namespace RRZE\RRZESearch\Application\Controller;

class AppController
{
    public $plugin_path;
    public $plugin_url;
    public $plugin;

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(\dirname(__FILE__, 3));
        $this->plugin_url = plugin_dir_url(\dirname(__FILE__, 4));
        $this->plugin = plugin_basename(\dirname(__FILE__, 5)) . '/rrze-search.php';
    }

    public function activated($key)
    {
        $option = get_option('rrze_search_settings');
        return $option[$key] ?? false;
    }
}