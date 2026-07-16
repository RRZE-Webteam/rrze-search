<?php

namespace RRZE\RRZESearch\Infrastructure;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Application\Controller\ShortcodeController;
use RRZE\RRZESearch\Application\Controller\WidgetController;
use RRZE\RRZESearch\Infrastructure\DashboardWidget;
use RRZE\RRZESearch\Infrastructure\UsageLimiter;

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
            ResultsPage::class,
            SettingsPage::class,
            ScriptEnqueuer::class,
            SettingsLink::class,
            UsageLimiter::class,
            DashboardWidget::class,
            WidgetController::class,
            ShortcodeController::class,
            BlockRegistration::class,
        ];
    }

    /**
     * Instantiates each service and calls its register hook when available.
     *
     * @return void
     */
    public static function bootstrap(): void
    {
        (new RRZESearchSettingsExtender())->extendWithGlobalEngines();

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
        if (!get_option('rrze_search_settings')) {
            update_option('rrze_search_settings', [
                'rrze_search_resources' => [],
                'rrze_search_engines' => [],
                'rrze_search_page_id' => 0,
                'rrze_search_default_engine' => '',
            ]);
        }

        ResultsPage::ensureExists();
        flush_rewrite_rules();
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
        ResultsPage::setStatus('private');
        flush_rewrite_rules();
    }
}
