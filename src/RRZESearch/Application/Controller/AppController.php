<?php

namespace RRZE\RRZESearch\Application\Controller;

/**
 * App Controller
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Application
 */
class AppController
{
    /**
     * Plugin path
     *
     * @var string
     */
    public $pluginPath;
    /**
     * Plugin URL
     *
     * @var string
     */
    public $pluginUrl;
    /**
     * Plugin entry file
     *
     * @var string
     */
    public $plugin;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pluginPath = plugin_dir_path(\dirname(__FILE__, 3));
        $this->pluginUrl  = plugin_dir_url(\dirname(__FILE__, 4));
        $this->plugin     = plugin_basename(\dirname(__FILE__, 5)).'/rrze-search.php';
    }

    /**
     * Test whether a particular plugin option is activated
     *
     * @param mixed $key Option key
     *
     * @return bool Option is activated
     */
    public function activated($key)
    {
        $option = get_option('rrze_search_settings');

        return $option[$key] ?? false;
    }
}