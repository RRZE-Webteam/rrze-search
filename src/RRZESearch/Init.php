<?php

namespace RRZE\RRZESearch;

final class Init
{
    public static function getServices()
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

    public static function registerServices()
    {
        foreach (self::getServices() as $class) {
            $service = new $class;
            if (is_callable([$service, 'register'])) {
                $service->register();
            }
        }
    }
}