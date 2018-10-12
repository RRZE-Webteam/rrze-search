<?php

namespace RRZE\RRZESearch\Infrastructure\Helper;

/**
 * Universal Helper
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class Helper
{

    public static function adapterCollection(): array
    {
        // Employed by AppController Class & SearchWidget Class
        $enginesClassCollection = [];

        // Define path to Adapter Class Directory
        $adapterDirectory = \dirname(__DIR__, 2).DIRECTORY_SEPARATOR.self::toDirectory([
                'Infrastructure',
                'Engines',
                'Adapters'
            ]);

        // Scan the directory for Search Engine resources (i.e. the Adapters)
        foreach (scandir($adapterDirectory, SCANDIR_SORT_NONE) as $adapterFile) {
            if ($adapterFile !== '.' && $adapterFile !== '..') {
                $engineName      = pathinfo($adapterFile, PATHINFO_FILENAME);
                $engineClassName = 'RRZE\\RRZESearch\\Infrastructure\\Engines\\Adapters\\'.$engineName;
                // Add to our array collection
                $enginesClassCollection[$engineClassName] = [
                    'name'       => \call_user_func([$engineClassName, 'getName']),
                    'label'      => \call_user_func([$engineClassName, 'getLabel']),
                    'link_label' => \call_user_func([$engineClassName, 'getLinkLabel']),
                ];
                if (is_callable([$engineClassName, 'getVariables'])){
                    $enginesClassCollection[$engineClassName]['variables'] = \call_user_func([$engineClassName, 'getVariables']);
                }
            }
        }
        return $enginesClassCollection;
    }
    /**
     * Check if a resource is an engine
     * TODO: Consider Cutting
     *
     * @param string $optionName Option name
     * @param string $resourceId Resource ID
     *
     * @return bool Is an engine
     */
    public static function isResourceEngine($optionName, $resourceId): bool
    {
        $bool   = false;
        $option = get_option($optionName);
        foreach ($option['rrze_search_engines'] as $engine) {
            if ($engine['resource_id'] === $resourceId) {
                $bool = true;
//                return $engine;
            }
        }

        return $bool;
    }

    /**
     * Check if an engine is a resource
     * TODO: Consider Cutting
     *
     * @param string $optionName Option name
     * @param string $resourceId Resource ID
     *
     * @return bool
     */
    public static function isEngineResource($optionName, $resourceId): bool
    {
        $optionValue = get_option($optionName);
        foreach ($optionValue['rrze_search_resources'] as $resource) {
            if ($resource['resource_id'] === $resourceId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return a resource by ID
     * TODO: Consider Cutting
     *
     * @param string $optionName Option name
     * @param string $resourceId Resource ID
     *
     * @return mixed
     * @throws \OutOfRangeException If the resource ID is unknown
     */
    public static function getResourceById($optionName, $resourceId)
    {
        $optionValue = get_option($optionName);
        foreach ($optionValue['rrze_search_resources'] as $resource) {
            if ($resource['resource_id'] === $resourceId) {
                return $resource;
            }
        }

        throw new \OutOfRangeException(sprintf('Unknown resource ID "%s"', $resourceId), 1538577491);
    }

    /**
     * Return an engine by ID
     *
     * @param string $optionName Option name
     * @param string $resourceId Resource ID
     *
     * @return mixed
     * @throws \OutOfRangeException If the engine ID is unknown
     */
    public static function getEngineById($optionName, $resourceId)
    {
        // Employed by OptionsSettings Class
        $optionValue = get_option($optionName);
        foreach ($optionValue['rrze_search_engines'] as $resource) {
            if ($resource['resource_id'] === $resourceId) {
                return $resource;
            }
        }

        throw new \OutOfRangeException(sprintf('Unknown engine ID "%s"', $resourceId), 1538577502);
    }

    /**
     * Return a Directory Path
     *
     * @param $folders
     *
     * @return string
     */
    public static function toDirectory($folders)
    {
        // Employed by OptionsFields Class
        return implode(DIRECTORY_SEPARATOR, $folders);
    }
}