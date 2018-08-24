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
            Infrastructure\DashboardLink::class,
            Infrastructure\DatabaseApi::class,
            Application\WidgetController::class,
            Application\ShortcodeController::class,
        ];
    }

    /**
     * Bootstrap the Plugin's Services
     */
    public static function registerServices(): void
    {
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
        echo 'Activate!';
        flush_rewrite_rules();

        if (!get_option('rrze_search_settings')){
            update_option('rrze_search_settings', []);
        }
    }

    /**
     * Plugin Deactivation
     */
    public static function deactivate(): void
    {
        flush_rewrite_rules();
    }
}