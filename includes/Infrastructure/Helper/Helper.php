<?php

namespace RRZE\RRZESearch\Infrastructure\Helper;
defined( 'ABSPATH' ) || exit;
/**
 * Provides shared utility functions used across the RRZE Search plugin.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class Helper
{
    /**
     * Builds a collection of engine adapter metadata indexed by class name.
     *
     * @return array<string, array<string, mixed>> Map of adapter class names to display metadata.
     */
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
     * Determines whether a resource entry also exists in the engine collection.
     *
     * @param string $optionName WordPress option that stores plugin settings.
     * @param string $resourceId Identifier assigned to the resource.
     *
     * @return bool True when the resource is registered as an engine.
     */
    public static function isResourceEngine(string $optionName, string $resourceId): bool
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
     * Determines whether an engine entry has a corresponding resource definition.
     *
     * @param string $optionName WordPress option that stores plugin settings.
     * @param string $resourceId Identifier assigned to the engine.
     *
     * @return bool True when the engine maps to a stored resource.
     */
    public static function isEngineResource(string $optionName, string $resourceId): bool
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
     * Retrieves a specific resource configuration by its identifier.
     *
     * @param string $optionName WordPress option that stores plugin settings.
     * @param string $resourceId Identifier assigned to the resource.
     *
     * @return array<string, mixed> Resource configuration array.
     *
     * @throws \OutOfRangeException If the resource ID is unknown.
     */
    public static function getResourceById(string $optionName, string $resourceId): array
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
     * Retrieves a specific engine configuration by its identifier.
     *
     * @param string $optionName WordPress option that stores plugin settings.
     * @param string $resourceId Identifier assigned to the engine.
     *
     * @return array<string, mixed> Engine configuration array.
     *
     * @throws \OutOfRangeException If the engine ID is unknown.
     */
    public static function getEngineById(string $optionName, string $resourceId): array
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
     * Converts an ordered list of directory segments into a path string.
     *
     * @param array<int, string> $folders Ordered path segments.
     *
     * @return string Platform-specific directory path.
     */
    public static function toDirectory(array $folders): string
    {
        // Employed by OptionsFields Class
        return implode(DIRECTORY_SEPARATOR, $folders);
    }
}
