<?php
declare(strict_types=1);

namespace RRZE\RRZESearch\Infrastructure;
defined('ABSPATH') || exit;

use RRZE\RRZESearch\Infrastructure\Helper\Helper;

/**
 * Refactored class to merge globally defined search engines into the rrze_search_settings option.
 *
 * - Preserves original variable names inside the main workflow.
 * - Adds small, focused helpers for readability and testability.
 * - Defensive checks and strict types for robustness.
 */
final class RRZESearchSettingsExtender
{
    /**
     * @var array<string, array<string, mixed>>
     */
    private array $adapterCollection;

    public function __construct()
    {
        $this->adapterCollection = Helper::adapterCollection();
    }

    /**
     * Public entry point (replacement for rrze_search_extend_with_global_engines()).
     */
    public function extendWithGlobalEngines(): void
    {
        $globalEngines = $this->getGlobalEngines();
        if ($globalEngines === []) {
            return;
        }

        $settings = $this->getSettings();

        $resources = $settings['rrze_search_resources'] ?? [];
        $engines   = $settings['rrze_search_engines'] ?? [];

        // Index existing resources by their adapter class for quick lookup.
        $resourceIndexByClass = $this->indexResourcesByClass($resources);

        // Index existing engines by resource_id so we can update in place.
        $engineIndexById = $this->indexEnginesById($engines);

        foreach ($globalEngines as $key => $presetRaw) {
            if (!is_array($presetRaw)) {
                continue;
            }

            $preset = $this->normalizePreset($presetRaw, $key);
            if ($preset === null) {
                // Missing resource_class (cannot target adapter).
                continue;
            }

            $resourceClass = $preset['resource_class'];
            $name          = $preset['name'];
            $desc          = $preset['desc'];
            $cx            = $preset['cx'];
            $api           = $preset['key'];
            $enabled       = "on";

            // Fill or create the resource row.
            if (isset($resourceIndexByClass[$resourceClass])) {
                $resourceIdx = $resourceIndexByClass[$resourceClass];
                $resource    = $resources[$resourceIdx];

                if (empty($resource['resource_name']) && $name !== '') {
                    $resource['resource_name'] = $name;
                }
                if (empty($resource['resource_disclaimer']) && $desc !== '') {
                    $resource['resource_disclaimer'] = $desc;
                }

                $resource['enabled'] = $enabled;

                $resource['args'] = $resource['args'] ?? [];
                if ($cx !== '' && empty($resource['args']['cx'])) {
                    $resource['args']['cx'] = $cx;
                }
                if ($api !== '' && empty($resource['args']['key'])) {
                    $resource['args']['key'] = $api;
                }

                $resources[$resourceIdx] = $resource;
            } else {
                $resourceId = $this->generateResourceId();

                $resources[] = [
                    'resource_id'         => $resourceId,
                    'resource_class'      => $resourceClass,
                    'resource_name'       => $name,
                    'resource_disclaimer' => $desc,
                    'args'                => array_filter(
                        [
                            'cx'  => $cx,
                            'key' => $api,
                        ],
                        static fn($value) => $value !== ''
                    ),
                ];

                // Store the index so we can relate an engine entry to this resource.
                $resourceIndexByClass[$resourceClass] = array_key_last($resources);
            }

            // Ensure a matching engine entry exists.
            $resourceIdx = $resourceIndexByClass[$resourceClass];
            $resourceId  = $resources[$resourceIdx]['resource_id'] ?? '';

            if ($resourceId === '') {
                // Defensive: if resource_id is missing for any reason, skip to avoid corrupting settings.
                continue;
            }

            if (isset($engineIndexById[$resourceId])) {
                $engineIdx = $engineIndexById[$resourceId];
            } else {
                $engines[] = [
                    'resource_id'    => $resourceId,
                    'resource_name'  => $resources[$resourceIdx]['resource_name'] ?? '',
                    'resource_class' => $resourceClass,
                    'enabled'        => true,
                    'args'           => [],
                ];
                $engineIdx = array_key_last($engines);
                $engineIndexById[$resourceId] = $engineIdx;
            }

            $engines[$engineIdx]['resource_name']  = $resources[$resourceIdx]['resource_name'] ?? ($engines[$engineIdx]['resource_name'] ?? '');
            $engines[$engineIdx]['resource_class'] = $resourceClass;
            if (!array_key_exists('enabled', $engines[$engineIdx])) {
                $engines[$engineIdx]['enabled'] = true;
            }
            $engines[$engineIdx]['args']           = $engines[$engineIdx]['args'] ?? [];

            if ($cx !== '' && empty($engines[$engineIdx]['args']['cx'])) {
                $engines[$engineIdx]['args']['cx'] = $cx;
            }
            if ($api !== '' && empty($engines[$engineIdx]['args']['key'])) {
                $engines[$engineIdx]['args']['key'] = $api;
            }
        }

        $settings['rrze_search_resources'] = array_values($resources);
        $settings['rrze_search_engines']   = array_values($engines);

        $this->updateSettings($settings);
    }

    /**
     * Safely fetch and validate the global engines definition (RRZE_SEARCH_ENGINES).
     *
     * @return array<array-key, array>
     */
    private function getGlobalEngines(): array
    {
        if (!defined('RRZE_SEARCH_ENGINES')) {
            return [];
        }

        /** @var mixed $maybe */
        $maybe = RRZE_SEARCH_ENGINES;
        if (!is_array($maybe)) {
            return [];
        }

        return $maybe;
    }

    /**
     * Retrieve settings with a strict default shape.
     *
     * @return array{
     *   rrze_search_resources: array<int, array>,
     *   rrze_search_engines: array<int, array>,
     *   rrze_search_page_id: int
     * }
     */
    private function getSettings(): array
    {
        $settings = get_option(
            'rrze_search_settings',
            [
                'rrze_search_resources' => [],
                'rrze_search_engines'   => [],
                'rrze_search_page_id'   => 0,
            ]
        );

        // Defensive normalization.
        if (!is_array($settings)) {
            $settings = [
                'rrze_search_resources' => [],
                'rrze_search_engines'   => [],
                'rrze_search_page_id'   => 0,
            ];
        }

        $settings['rrze_search_resources'] = isset($settings['rrze_search_resources']) && is_array($settings['rrze_search_resources'])
            ? $settings['rrze_search_resources']
            : [];

        $settings['rrze_search_engines'] = isset($settings['rrze_search_engines']) && is_array($settings['rrze_search_engines'])
            ? $settings['rrze_search_engines']
            : [];

        $settings['rrze_search_page_id'] = isset($settings['rrze_search_page_id']) && is_int($settings['rrze_search_page_id'])
            ? $settings['rrze_search_page_id']
            : 0;

        return $settings;
    }

    /**
     * Persist settings.
     *
     * @param array $settings
     */
    private function updateSettings(array $settings): void
    {
        update_option('rrze_search_settings', $settings);
    }

    /**
     * @param array<int, array> $resources
     * @return array<string, int> Map: resource_class => index
     */
    private function indexResourcesByClass(array $resources): array
    {
        $resourceIndexByClass = [];

        foreach ($resources as $index => $resource) {
            if (!is_array($resource)) {
                continue;
            }
            if (!empty($resource['resource_class']) && is_string($resource['resource_class'])) {
                $resourceIndexByClass[$resource['resource_class']] = $index;
            }
        }

        return $resourceIndexByClass;
    }

    /**
     * @param array<int, array> $engines
     * @return array<string, int> Map: resource_id => index
     */
    private function indexEnginesById(array $engines): array
    {
        $engineIndexById = [];

        foreach ($engines as $index => $engine) {
            if (!is_array($engine)) {
                continue;
            }
            if (!empty($engine['resource_id']) && is_string($engine['resource_id'])) {
                $engineIndexById[$engine['resource_id']] = $index;
            }
        }

        return $engineIndexById;
    }

    /**
     * Normalize a single preset into a well-defined structure.
     *
     * @param array $presetRaw
     * @param mixed $key
     * @return array{name:string,desc:string,cx:string,key:string,resource_class:string}|null
     */
    private function normalizePreset(array $presetRaw, $key): ?array
    {
        $resourceClass = $this->resolveResourceClass($presetRaw, $key);
        if ($resourceClass === null) {
            return null;
        }

        $name = isset($presetRaw['name']) && is_string($presetRaw['name']) ? $presetRaw['name'] : '';
        $desc = isset($presetRaw['desc']) && is_string($presetRaw['desc']) ? $presetRaw['desc'] : '';
        $cx   = isset($presetRaw['cx']) && is_string($presetRaw['cx']) ? $presetRaw['cx'] : '';

        // Accept key | api | apikey
        $api  = '';
        if (isset($presetRaw['key']) && is_string($presetRaw['key'])) {
            $api = $presetRaw['key'];
        } elseif (isset($presetRaw['api']) && is_string($presetRaw['api'])) {
            $api = $presetRaw['api'];
        } elseif (isset($presetRaw['apikey']) && is_string($presetRaw['apikey'])) {
            $api = $presetRaw['apikey'];
        }

        return [
            'name'           => $name,
            'desc'           => $desc,
            'cx'             => $cx,
            'key'            => $api,
            'resource_class' => $resourceClass,
            'enabled'        => "on",
        ];
    }

    /**
     * Resolve resource class name from preset or key.
     *
     * @param array $preset
     * @param mixed $key
     */
    private function resolveResourceClass(array $preset, $key): ?string
    {
        if (isset($preset['resource_class']) && is_string($preset['resource_class']) && $preset['resource_class'] !== '') {
            return $preset['resource_class'];
        }

        if (isset($preset['class']) && is_string($preset['class']) && $preset['class'] !== '') {
            return $preset['class'];
        }

        if (is_string($key) && $key !== '') {
            return $key;
        }

        // Attempt to match by preset name against registered adapters.
        if (!empty($this->adapterCollection)) {
            $presetName = isset($preset['name']) && is_string($preset['name']) ? $preset['name'] : '';
            if ($presetName !== '') {
                foreach ($this->adapterCollection as $class => $meta) {
                    $candidateNames = array_filter([
                        $meta['name'] ?? null,
                        $meta['label'] ?? null,
                    ]);

                    foreach ($candidateNames as $candidate) {
                        if (is_string($candidate) && strcasecmp($candidate, $presetName) === 0) {
                            return $class;
                        }
                    }
                }
            }

            // Fallback to the first available adapter when everything else fails.
            $firstAdapter = array_key_first($this->adapterCollection);
            if (is_string($firstAdapter) && $firstAdapter !== '') {
                return $firstAdapter;
            }
        }

        return null;
    }

    /**
     * Generate a unique resource id in a WP-safe way.
     */
    private function generateResourceId(): string
    {
        if (function_exists('wp_unique_id')) {
            /** @psalm-suppress MixedArgument */
            return (string) wp_unique_id('rrze_');
        }

        return uniqid('rrze_', true);
    }
}
