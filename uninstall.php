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
    $resultsPageId = (int) $options['rrze_search_page_id'];
    $isManagedResultsPage = get_post_type($resultsPageId) === 'page'
        && get_post_meta($resultsPageId, '_rrze_search_managed_page', true) === '1';

    if ($isManagedResultsPage) {
        wp_delete_post($resultsPageId, true);
    }
}

// WP Delete Plugin Option
delete_option($pluginOption);
delete_option($pluginWidget);

// WP Delete Plugin Option from Multisite
delete_site_option($pluginId);
delete_site_option($pluginWidget);
