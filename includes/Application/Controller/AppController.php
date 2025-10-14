<?php

namespace RRZE\RRZESearch\Application\Controller;

use RRZE\RRZESearch\Infrastructure\Helper\Helper;

/**
 * Base controller providing shared plugin metadata and helpers.
 *
 * Calculates canonical paths, URLs, and engine collections so concrete
 * controllers can focus on registering widgets, shortcodes, or dashboards.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Application
 */
class AppController
{
    /**
     * Absolute path to the plugin root directory.
     *
     * @var string
     */
    public $pluginPath;
    /**
     * Public URL pointing to the plugin root directory.
     *
     * @var string
     */
    public $pluginUrl;
    /**
     * Plugin basename used for registration and activation checks.
     *
     * @var string
     */
    public $plugin;
    /**
     * Collection of available search engine adapters keyed by class name.
     *
     * @var array<string, array<string, mixed>>
     */
    public $enginesClassCollection = [];

    /**
     * Bootstraps plugin metadata and the available engine adapters.
     */
    public function __construct()
    {
        /** @var string pluginPath: /www/accounts/fau/data/wp-content/plugins/rrze-search/src/ */
        $this->pluginPath = plugin_dir_path(\dirname(__FILE__, 2));

        /** @var string pluginUrl: https://[servername.net]/wp-content/plugins/rrze-search/ */

        $this->pluginUrl  = plugin_dir_url(\dirname(__FILE__, 3));
        /** @var string plugin: rrze-search/rrze-search.php */

        $this->plugin     = plugin_basename(\dirname(__FILE__, 4)).'/rrze-search.php';
        $this->enginesClassCollection = Helper::adapterCollection();
    }

    /**
     * Checks whether a particular plugin option flag is enabled.
     *
     * @param string $key Option identifier to inspect.
     *
     * @return bool True when the option exists and evaluates to truthy.
     */
    public function activated(string $key): bool
    {
        $option = get_option('rrze_search_settings');

        return $option[$key] ?? false;
    }
}
