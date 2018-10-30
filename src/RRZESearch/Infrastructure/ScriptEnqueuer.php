<?php

namespace RRZE\RRZESearch\Infrastructure;

use RRZE\RRZESearch\Application\Controller\AppController;

/**
 * Script Enqueuer
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class ScriptEnqueuer extends AppController
{
    /**
     * Registration
     */
    public function register() {
        add_action('wp_enqueue_scripts', [$this, 'enqueuePluginScripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
    }

    /**
     * Enqueue the admin scripts
     */
    public function enqueueAdminScripts()
    {
        wp_enqueue_style('rrze-search-style', $this->pluginUrl.'assets/css/rrze-search-style.css');
        wp_enqueue_script('rrze-search-script', $this->pluginUrl.'assets/js/rrze-search-admin-script.min.js', '', false,
            true);
    }

    /**
     * Enqueue the plugin scripts
     */
    public function enqueuePluginScripts()
    {
        wp_enqueue_style('rrze-search-style', $this->pluginUrl.'assets/css/rrze-search-style.css');
        wp_enqueue_script(
            'rrze-search-script-a11y',
            $this->pluginUrl.'assets/js/ally.min.js',
            ['fau-scripts'],
            false,
            true
        );
        wp_enqueue_script(
            'rrze-search-script',
            $this->pluginUrl.'assets/js/rrze-search-script.min.js',
            ['rrze-search-script-a11y'],
            false,
            true
        );
    }
    
}