<?php

namespace RRZE\RRZESearch\Infrastructure;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Application\Controller\AppController;

/**
 * Registers the public and admin assets required by RRZE Search.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class ScriptEnqueuer extends AppController
{
    /**
     * Wires WordPress hooks so plugin and admin assets load when needed.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueuePluginScripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
    }

    /**
     * Enqueues styles and scripts needed for the RRZE Search admin settings pages.
     *
     * @return void
     */
    public function enqueueAdminScripts(): void
    {
        wp_enqueue_style('rrze-search-style', $this->pluginUrl . 'css/rrze-search.css');
        wp_enqueue_script('rrze-search-script', $this->pluginUrl . 'js/rrze-search-admin.js', [], false, true);
    }

    /**
     * Enqueues the public-facing RRZE Search assets and dependencies.
     *
     * @return void
     */
    public function enqueuePluginScripts(): void
    {
        wp_enqueue_style('rrze-search-style', $this->pluginUrl . 'css/rrze-search.css');
        wp_enqueue_script('rrze-search-script-a11y', $this->pluginUrl . 'js/ally.min.js', ['fau-scripts'], false, true);
        wp_enqueue_script('rrze-search-script', $this->pluginUrl . 'js/rrze-search.js', ['rrze-search-script-a11y'], false, true);
    }
}
