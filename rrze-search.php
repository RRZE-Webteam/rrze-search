<?php

/*
Plugin Name: RRZE Search
Plugin URI: https://www.tollwerk.de
description: a WordPress Search Plugin by tollwerk GmbH
Author: tollwerk
Author URI: https://www.tollwerk.de
Version: 0.0.1
License: GPL2
Text Domain: rrze-search
Domain Path: /languages
*/

// Prevent abuse
defined('ABSPATH') || exit;

// Include composer autoloader
if (file_exists(dirname(__FILE__).'/vendor/autoload.php')) {
    require_once(dirname(__FILE__).'/vendor/autoload.php');
}

/**
 * Plugin Activation Function
 */
function activate_rrze_search_plugin()
{
    RRZE\RRZESearch\Ports\Multisearch::activate();
}

// WP Activation Hook
register_activation_hook(__FILE__, 'activate_rrze_search_plugin');

/**
 * Plugin Deactivation Function
 */
function deactivate_rrze_search_plugin()
{
    RRZE\RRZESearch\Ports\Multisearch::deactivate();
}

// WP Deactivation Hook
register_deactivation_hook(__FILE__, 'deactivate_rrze_search_plugin');

// Bootstrap the Plugin
if (class_exists(\RRZE\RRZESearch\Ports\Multisearch::class)) {
    RRZE\RRZESearch\Ports\Multisearch::bootstrap();
}

