<?php

namespace RRZE\RRZESearch\Infrastructure;

use RRZE\RRZESearch\Application\Controller\ShortcodeController;
use RRZE\RRZESearch\Application\Controller\WidgetController;

/**
 * Facade that wires the RRZE Search plugin services into WordPress.
 *
 * The class centralizes the registration of dashboard integrations, scripts,
 * settings links, widgets, and shortcodes so they can be bootstrapped during
 * plugin load, activation, and deactivation events.
 *
 * @package RRZE\RRZESearch
 */
class ServiceProvider
{
    /**
     * Returns the list of service classes that should be registered.
     *
     * @return array<int, class-string> Ordered list of service class names.
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
     * Instantiates each service and calls its register hook when available.
     *
     * @return void
     */
    public static function bootstrap(): void
    {
        foreach (static::getServices() as $class) {
            $service = new $class;
            if (\is_callable([$service, 'register'])) {
                $service->register();
            }
        }
    }

    /**
     * Handles plugin activation by flushing rewrite rules and seeding defaults.
     *
     * Ensures the settings option exists with baseline values and publishes the
     * RRZE Search results page so frontend queries resolve correctly.
     *
     * @return void
     */
    public static function activate(): void
    {
        flush_rewrite_rules();

        if (!get_option('rrze_search_settings')) {
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
     * Handles plugin deactivation by flushing rewrite rules and hiding outputs.
     *
     * Transitions the RRZE Search results page to a private status to prevent
     * public access while the plugin remains inactive.
     *
     * @return void
     */
    public static function deactivate(): void
    {
        flush_rewrite_rules();
        self::updateResultsPageStatus('private');
    }

    /**
     * Updates the stored results page post status while preserving settings.
     *
     * @param string $status WordPress post status to assign (for example 'publish' or 'private').
     *
     * @return void
     */
    private static function updateResultsPageStatus(string $status): void
    {
        $options = get_option('rrze_search_settings');

        if (!empty($options['rrze_search_page_id'])) {
            $pageId = $options['rrze_search_page_id'];

            if ($pageId !== '') {
                $page = get_post($pageId, 'ARRAY_A');
                $page['post_status'] = $status;
            }

            wp_update_post($page);
        }
    }
}
