<?php
/*
Plugin Name: RRZE Search
Plugin URI: https://www.tollwerk.de
description: A WordPress Search Plugin originally by Tollwerk GmbH and maintained by RRZE
Author: Tollwerk & RRZE
Author URI: https://www.tollwerk.de
Version: 1.0.2
License: GPL3
Text Domain: rrze-search
Domain Path: /languages
*/

defined('ABSPATH') || exit;

const RRZE_PHP_VERSION = '8.2';
const RRZE_WP_VERSION = '6.7';

use RRZE\RRZESearch\Infrastructure\ServiceProvider;

// Loading constants
include_once('constants.php');

// ==================================================
// Actions and Activation / Deactivation Hooks
// ==================================================

add_action('plugins_loaded', 'rrze_search_init');
add_action('init', 'rrze_search_load_textdomain');

register_activation_hook(__FILE__, 'rrze_search_plugin_activation');
register_deactivation_hook(__FILE__, 'rrze_search_plugin_deactivation');

// ==================================================
// Plugin Initialization, Activation and Deactivation
// ==================================================

/**
 * Initializes the Plugin
 *
 * Runs on plugins_loaded WP action and initializes the Plugin.
 * It relies on the activation function that was run previously to setup the Options.
 * @return void
 */
function rrze_search_init(): void
{
    // Include composer autoloader
    if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
        require_once(dirname(__FILE__) . '/vendor/autoload.php');
    }

    // Bootstrap the Plugin
    if (class_exists(\RRZE\RRZESearch\Infrastructure\ServiceProvider::class)) {
        ServiceProvider::bootstrap();
    }
}

/**
 * Plugin Activation Function
 */
function rrze_search_plugin_activation(): void
{
    rrze_search_load_textdomain();
    rrze_search_check_system_requirements();
    rrze_search_include_autoloader();

    ServiceProvider::activate();
}

/**
 * Plugin Deactivation Function
 */
function rrze_search_plugin_deactivation(): void
{
    rrze_search_include_autoloader();
    ServiceProvider::deactivate();
}

// ==================================================
// Helper functions | Autoload | System Check | Textdomain Loading
// ==================================================
/**
 * Include the Composer Autoloader
 */
function rrze_search_include_autoloader(): void
{
    if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
        require_once(dirname(__FILE__) . '/vendor/autoload.php');
    }
}

/**
 * Load the RRZE Search Textdomain for l10n
 * @return void
 */
function rrze_search_load_textdomain(): void
{
    load_plugin_textdomain('rrze-search', FALSE, sprintf('%s/languages/', dirname(plugin_basename(__FILE__))));
}

/**
 * Checks the System Requirements and deactivates the Plugin in case of violation
 *
 * Checks the System Requirements for in L16,L17 defined WP- and PHP-Version.
 * If the check fails, the Plugin is deactivated network-wide
 * @return void
 */
function rrze_search_check_system_requirements(): void
{
    $error = '';
    if (version_compare(PHP_VERSION, RRZE_PHP_VERSION, '<')) {
        $error = sprintf(__('Your server is running PHP version %s. Please upgrade at least to PHP version %s.', 'rrze-test'), PHP_VERSION, RRZE_PHP_VERSION);
    }

    if (version_compare($GLOBALS['wp_version'], RRZE_WP_VERSION, '<')) {
        $error = sprintf(__('Your Wordpress version is %s. Please upgrade at least to Wordpress version %s.', 'rrze-test'), $GLOBALS['wp_version'], RRZE_WP_VERSION);
    }

    if (!empty($error)) {
        deactivate_plugins(plugin_basename(__FILE__), FALSE, TRUE);
        wp_die($error);
    }
}