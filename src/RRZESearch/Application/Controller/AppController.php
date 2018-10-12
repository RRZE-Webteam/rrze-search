<?php

namespace RRZE\RRZESearch\Application\Controller;

use RRZE\RRZESearch\Infrastructure\Helper\Helper;

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
     * Registered search engines
     *
     * @var array[]
     */
    public $enginesClassCollection = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        /** @var string pluginPath: /www/accounts/fau/data/wp-content/plugins/rrze-search/src/ */
        $this->pluginPath = plugin_dir_path(\dirname(__FILE__, 3));
        /** @var string pluginUrl: https://[servername.net]/wp-content/plugins/rrze-search/ */
        $this->pluginUrl  = plugin_dir_url(\dirname(__FILE__, 4));
        /** @var string plugin: rrze-search/rrze-search.php */
        $this->plugin     = plugin_basename(\dirname(__FILE__, 5)).'/rrze-search.php';

        $this->enginesClassCollection = Helper::adapterCollection();
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