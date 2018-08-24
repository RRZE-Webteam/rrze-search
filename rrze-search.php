<?php
/*
Plugin Name: RRZE Search
Plugin URI: https://www.tollwerk.de
description: a WordPress Search Plugin by tollwerk GmbH
Author: tollwerk
Author URI: https://www.tollwerk.de
Version: 0.0.1
License: GPL2
*/

/**
 * @package RRZESearch
 */

defined('ABSPATH') || exit;

if (file_exists(dirname(__FILE__).'/vendor/autoload.php')) {
    require_once(dirname(__FILE__).'/vendor/autoload.php');
}

function activate_rrze_search_plugin() {
    RRZE\RRZESearch\Init::activate();
}
register_activation_hook( __FILE__, 'activate_rrze_search_plugin' );

function deactivate_rrze_search_plugin() {
    RRZE\RRZESearch\Init::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_rrze_search_plugin' );


if (class_exists(\RRZE\RRZESearch\Init::class)) {
    RRZE\RRZESearch\Init::registerServices();
}

