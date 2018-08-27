<?php

/**
 * @package RRZESearch
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

/** @var string $option_name */
$option_name = 'rrze_search_settings';

/**
 * WP Delete Plugin Option
 */
delete_option($option_name);
delete_option('widget_'.$option_name);

/**
 * WP Delete Plugin Option from Multisite
 */
delete_site_option($option_name);