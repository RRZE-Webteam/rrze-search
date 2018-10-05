<?php

/**
 * @package RRZESearch
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

/** @var string $pluginId */
$pluginId     = 'rrze_search';
$pluginOption = $pluginId.'_settings';
$pluginWidget = 'widget_'.$pluginId;

// Remove Search Results Page
$options = get_option($pluginOption);
if (isset($options['rrze_search_page_id'])) {
    wp_delete_post((int)$options['rrze_search_page_id'], true);
}

// WP Delete Plugin Option
delete_option($pluginOption);
delete_option($pluginWidget);

// WP Delete Plugin Option from Multisite
delete_site_option($pluginId);
delete_site_option($pluginWidget);
