<?php

namespace RRZE\RRZESearch\Infrastructure\Helper;


class Helper
{
    /**
     * Check if a Resource is an Engine
     *
     * @param $option_name
     * @param $resource_id
     *
     * @return bool
     */
    public static function isResourceEngine($option_name, $resource_id): bool
    {
        $bool         = false;
        $option = get_option($option_name);
        foreach ($option['rrze_search_engines'] as $engine) {
            if ($engine['resource_id'] === $resource_id) {
                $bool = true;
            }
        }

        return $bool;
    }

    /**
     * Check if an Engine is a Resource
     *
     * @param $option_name
     * @param $resource_id
     *
     * @return bool
     */
    public static function isEngineResource($option_name, $resource_id): bool
    {
        $bool         = false;
        $option_value = get_option($option_name);
        foreach ($option_value['rrze_search_resources'] as $resource) {
            if ($resource['resource_id'] === $resource_id) {
                $bool = true;
            }
        }

        return $bool;
    }

    public static function getResourceById($option_name, $resource_id)
    {
        $option_value = get_option($option_name);
        foreach ($option_value['rrze_search_resources'] as $resource) {
            if ($resource['resource_id'] === $resource_id) {
                return $resource;
            }
        }
    }

    public static function getEngineById($option_name, $resource_id)
    {
        $option_value = get_option($option_name);
        foreach ($option_value['rrze_search_engines'] as $resource) {
            if ($resource['resource_id'] === $resource_id) {
                return $resource;
            }
        }
    }
}