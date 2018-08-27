<?php

/**
 * @package RRZESearch
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

/** @var string $plugin_id */
$plugin_id     = 'rrze_search';
$plugin_option = $plugin_id.'_settings';
$plugin_widget = 'widget_'.$plugin_id;
/**
 * WP Delete Plugin Option
 */
delete_option($plugin_option);
delete_option($plugin_widget);

/**
 * WP Delete Plugin Option from Multisite
 */
delete_site_option($plugin_id);
delete_site_option($plugin_widget);