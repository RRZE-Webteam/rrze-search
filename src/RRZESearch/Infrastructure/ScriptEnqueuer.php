<?php

namespace RRZE\RRZESearch\Infrastructure;

use RRZE\RRZESearch\Application\AppController;

class ScriptEnqueuer extends AppController
{
    public function register()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueuePluginScripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
    }

    public function enqueueAdminScripts()
    {
        wp_enqueue_style('rrze-search-style', $this->plugin_url.'assets/css/rrze-search-style.css');
        wp_enqueue_script('rrze-search-script', $this->plugin_url.'assets/js/rrze-search-admin-script.js', '', false,
            true);
    }

    public function enqueuePluginScripts()
    {
        wp_enqueue_style('rrze-search-style', $this->plugin_url.'assets/css/rrze-search-style.css');
        wp_enqueue_script('rrze-search-script', $this->plugin_url.'assets/js/rrze-search-script.js', '', false, true);
        wp_enqueue_script('rrze-search-script-a11y', $this->plugin_url.'assets/js/ally.js', '', false, true);
    }
}