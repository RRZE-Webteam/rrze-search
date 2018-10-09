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

        // Define path to Adapter Class Directory
        $adapterDirectory = \dirname(__DIR__, 2).DIRECTORY_SEPARATOR.Helper::toDirectory([
                'Infrastructure',
                'Engines',
                'Adapters'
            ]);

        // Scan the directory for Search Engine resources (i.e. the Adapters)
        foreach (scandir($adapterDirectory, SCANDIR_SORT_NONE) as $adapterFile) {
            if ($adapterFile !== '.' && $adapterFile !== '..') {
                $engineName      = pathinfo($adapterFile, PATHINFO_FILENAME);
                $engineClassName = 'RRZE\\RRZESearch\\Infrastructure\\Engines\\Adapters\\'.$engineName;
                // Add to our array collection
                $this->enginesClassCollection[$engineClassName] = [
                    'name'       => \call_user_func([$engineClassName, 'getName']),
                    'label'      => \call_user_func([$engineClassName, 'getLabel']),
                    'link_label' => \call_user_func([$engineClassName, 'getLinkLabel'])
                ];
            }
        }
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