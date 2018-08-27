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
 * Remove Search Results Page
 */
global $wpdb;
$options = get_option($plugin_option);
if (isset($options['rrze_search_page_id'])) {
    wp_delete_post((int)$options['rrze_search_page_id'], true);
}

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
