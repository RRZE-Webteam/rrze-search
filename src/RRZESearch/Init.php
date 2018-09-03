<?php

namespace RRZE\RRZESearch;

/**
 * Class Init
 * Bootstrap the Plugin
 *
 * @package RRZE\RRZESearch
 */
final class Init
{
    /**
     * Return Services Array for Bootstrap
     *
     * @return array
     */
    public static function getServices(): array
    {
        return [
            Infrastructure\Dashboard::class,
            Infrastructure\ScriptEnqueuer::class,
            Infrastructure\SettingsLink::class,
            Application\WidgetController::class,
            Application\ShortcodeController::class,
        ];
    }

    /**
     * Bootstrap the Plugin's Services
     */
    public static function bootstrap(): void
    {
        /**
         * Pre-load Language Pack to ensure translation before bootstrapping
         */
        $languagePath = sprintf('%s/languages/', dirname(plugin_basename(__FILE__), 3));
        load_plugin_textdomain('rrze-search', false, $languagePath);

        foreach (self::getServices() as $class) {
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

        /** Validate Settings Option Exists */
        if (!get_option('rrze_search_settings')) {

            /** Enter Default Values */
            update_option('rrze_search_settings', [
                'rrze_search_resources' => [
                    ['resource_name' => 'Default', 'resource_key' => '']
                ]
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
        $page_id = $options['rrze_search_page_id'];

        if ($page_id !== '') {
            $page                = get_post($page_id, 'ARRAY_A');
            $page['post_status'] = $status;
        }

        wp_update_post($page);
    }
}