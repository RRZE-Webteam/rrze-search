<?php

namespace RRZE\RRZESearch\Ports;

use RRZE\RRZESearch\Application\Controller\ShortcodeController;
use RRZE\RRZESearch\Application\Controller\WidgetController;
use RRZE\RRZESearch\Infrastructure\Dashboard;
use RRZE\RRZESearch\Infrastructure\ScriptEnqueuer;
use RRZE\RRZESearch\Infrastructure\SettingsLink;

/**
 * Multisearch facade
 *
 * @package RRZE\RRZESearch
 */
class Multisearch
{
    /**
     * Return Services Array for Bootstrap
     *
     * @return array
     */
    public static function getServices(): array
    {
        return [
            Dashboard::class,
            ScriptEnqueuer::class,
            SettingsLink::class,
            WidgetController::class,
            ShortcodeController::class,
        ];
    }

    /**
     * Bootstrap the Plugin's Services
     */
    public static function bootstrap(): void
    {
        // Pre-load Language Pack to ensure translation before bootstrapping
        $languagePath = sprintf('%s/languages/', dirname(plugin_basename(__FILE__), 3));
        load_plugin_textdomain('rrze-search', false, $languagePath);

        // Run through all services
        foreach (static::getServices() as $class) {
            $service = new $class;
            if (\is_callable([$service, 'register'])) {
                $service->register();
            }
        }
    }

    /**
     * Plugin Activation
     */
    public static function activate(): void
    {
        flush_rewrite_rules();

        // Validate Settings Option Exists
        if (!get_option('rrze_search_settings')) {

            // Enter Default Values
            update_option('rrze_search_settings', [
                'rrze_search_resources' => [
                    ['resource_name' => 'Default', 'resource_key' => '']
                ],
                'rrze_search_engines' => []
            ]);
        }
        self::updateResultsPageStatus('publish');
    }

    /**
     * Plugin Deactivation
     */
    public static function deactivate(): void
    {
        flush_rewrite_rules();
//        deactivate_plugins('rrze-search/rrze-search.php');
//        unregister_sidebar('rrze-search-sidebar');
        self::updateResultsPageStatus('private');
    }

    /**
     * Update Result's Page Status
     *
     * @param string $status
     */
    private static function updateResultsPageStatus($status): void
    {
        $options = get_option('rrze_search_settings');
        $pageId  = $options['rrze_search_page_id'];

        if ($pageId !== '') {
            $page                = get_post($pageId, 'ARRAY_A');
            $page['post_status'] = $status;
        }

        wp_update_post($page);
    }
}