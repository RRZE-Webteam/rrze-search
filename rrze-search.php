<?php

/*
Plugin Name: RRZE Search
Plugin URI: https://www.tollwerk.de
description: a WordPress Search Plugin by tollwerk GmbH
Author: tollwerk
Author URI: https://www.tollwerk.de
Version: 0.1.3
License: GPL2
Text Domain: rrze-search
Domain Path: /languages
*/


const RRZE_PHP_VERSION = '7.0';
const RRZE_WP_VERSION = '4.9';

// Loading constants
include_once('constants.php');
 
add_action('plugins_loaded', 'rrze_search_init');
// WP Activation Hook
register_activation_hook(__FILE__, 'activate_rrze_search_plugin');
// WP Deactivation Hook
register_deactivation_hook(__FILE__, 'deactivate_rrze_search_plugin');


/**
 * Init
 */
function rrze_search_init() {
    rrze_search_textdomain();
    
    // Include composer autoloader
    if (file_exists(dirname(__FILE__).'/vendor/autoload.php')) {
	require_once(dirname(__FILE__).'/vendor/autoload.php');
    }
    
    // Bootstrap the Plugin
    if (class_exists(\RRZE\RRZESearch\Ports\Multisearch::class)) {
	RRZE\RRZESearch\Ports\Multisearch::bootstrap();
    }
}



/**
 * Plugin Activation Function
 */
function activate_rrze_search_plugin() {
    rrze_search_textdomain();
    system_requirements();
    
    RRZE\RRZESearch\Ports\Multisearch::activate();
}



/**
 * Plugin Deactivation Function
 */
function deactivate_rrze_search_plugin() {
    RRZE\RRZESearch\Ports\Multisearch::deactivate();
}


function rrze_search_textdomain() {
	load_plugin_textdomain('rrze-search', FALSE, sprintf('%s/languages/', dirname(plugin_basename(__FILE__))));
}

function system_requirements() {
    $error = '';

    if (version_compare(PHP_VERSION, RRZE_PHP_VERSION, '<')) {
	$error = sprintf(__('Your server is running PHP version %s. Please upgrade at least to PHP version %s.', 'rrze-test'), PHP_VERSION, RRZE_PHP_VERSION);
    }

    if (version_compare($GLOBALS['wp_version'], RRZE_WP_VERSION, '<')) {
	$error = sprintf(__('Your Wordpress version is %s. Please upgrade at least to Wordpress version %s.', 'rrze-test'), $GLOBALS['wp_version'], RRZE_WP_VERSION);
    }

    // Wenn die Überprüfung fehlschlägt, dann wird das Plugin automatisch deaktiviert.
    if (!empty($error)) {
	deactivate_plugins(plugin_basename(__FILE__), FALSE, TRUE);
	wp_die($error);
    }
}


// Prevent abuse
defined('ABSPATH') || exit;
