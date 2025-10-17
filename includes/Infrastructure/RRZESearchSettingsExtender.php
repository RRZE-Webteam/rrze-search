<?php
declare(strict_types=1);

namespace RRZE\RRZESearch\Infrastructure;
defined('ABSPATH') || exit;

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
     * Registered adapter metadata keyed by class name.
     *
     * @var array<string, array<string, mixed>>
     */
    private array $adapterCollection;

    public function __construct()
    {
        $this->adapterCollection = Helper\Helper::adapterCollection();
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

        $globalEngines = $this->dedupePresetsByClass($globalEngines);

        $settings = $this->getSettings();

        $resources = $settings['rrze_search_resources'] ?? [];
        $engines   = $settings['rrze_search_engines'] ?? [];

        // Index maps
        $resourceIndexByClass = $this->indexResourcesByClass($resources);

        $engineIndexById = $this->indexEnginesById($engines);

        foreach ($globalEngines as $key => $presetRaw) {
            if (!is_array($presetRaw)) {
                continue;
            }

            $preset = $this->normalizePreset($presetRaw, $key);
            if ($preset === null) {
                continue;
            }

            $resourceClass = $preset['resource_class'];
            $name          = $preset['name'] ?? '';
            $desc          = $preset['desc'] ?? '';
            $cx            = $preset['cx']   ?? '';

            $api           = $preset['key']  ?? ($preset['api'] ?? ($preset['apikey'] ?? ''));
            $enabled       = true;

            if (isset($resourceIndexByClass[$resourceClass])) {
                $resourceIdx = $resourceIndexByClass[$resourceClass];
                $resource    = $resources[$resourceIdx];

                // Overwrite policy for metadata if global provides values
                if ($name !== '') {
                    $resource['resource_name'] = $name;
                }
                if ($desc !== '') {
                    $resource['resource_disclaimer'] = $desc;
                }

                if (!isset($resource['enabled'])) {
                    $resource['enabled'] = $enabled;
                }

                $resource['args'] = $resource['args'] ?? [];

                // Overwrite policy for credentials if global provides values
                if ($cx !== '') {
                    $resource['args']['cx'] = $cx;
                }
                if ($api !== '') {
                    $resource['args']['key'] = $api; // align to local storage key
                }

                $resources[$resourceIdx] = $resource;
            } else {
                $resourceId = $this->generateResourceId();

                $resources[] = [
                    'resource_id'         => $resourceId,
                    'resource_class'      => $resourceClass,
                    'resource_name'       => $name,
                    'resource_disclaimer' => $desc,
                    'enabled'             => $enabled,
                    'args'                => array_filter(
                        [
                            'cx'  => (string) $cx,
                            'key' => (string) $api,
                        ],
                        static fn($value) => $value !== ''
                    ),
                ];

                $resourceIndexByClass[$resourceClass] = array_key_last($resources);
            }

            // Ensure a matching engine entry exists
            $resourceIdx = $resourceIndexByClass[$resourceClass];
            $resourceId  = $resources[$resourceIdx]['resource_id'] ?? '';

            if ($resourceId === '') {
                // Defensive: skip if we somehow lack a resource_id
                continue;
            }

            $resourceIdKey = (string) $resourceId; // normalize for index map

            if (isset($engineIndexById[$resourceIdKey])) {
                $engineIdx = $engineIndexById[$resourceIdKey];
            } else {
                $engines[] = [
                    'resource_id'    => $resourceId,
                    'resource_name'  => $resources[$resourceIdx]['resource_name'] ?? '',
                    'resource_class' => $resourceClass,
                    'enabled'        => true,
                    'args'           => [],
                ];
                $engineIdx = array_key_last($engines);
                $engineIndexById[$resourceIdKey] = $engineIdx; // keep map in sync
            }

            // Keep name/class in sync with resource
            $engines[$engineIdx]['resource_name']  = $resources[$resourceIdx]['resource_name'] ?? ($engines[$engineIdx]['resource_name'] ?? '');
            $engines[$engineIdx]['resource_class'] = $resourceClass;

            if (!array_key_exists('enabled', $engines[$engineIdx])) {
                $engines[$engineIdx]['enabled'] = true;
            }

            $engines[$engineIdx]['args'] = $engines[$engineIdx]['args'] ?? [];

            // Overwrite policy for credentials if global provides values
            if ($cx !== '') {
                $engines[$engineIdx]['args']['cx'] = $cx;
            }
            if ($api !== '') {
                $engines[$engineIdx]['args']['key'] = $api; // align to local storage key
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
        $global = [];

        if (defined('RRZE_SEARCH_ENGINES') && is_array(RRZE_SEARCH_ENGINES)) {
            $global = RRZE_SEARCH_ENGINES;
        }

        return array_merge($global, $this->buildDefaultLocalEngine());
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
            $resourceClass = $this->findWordPressAdapter();
            if ($resourceClass === null) {
                return null;
            }
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
            'enabled'        => true,
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

        return $this->findWordPressAdapter();
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

    /**
     * Create a preset entry describing the native WordPress search engine.
     *
     * @return array<int, array<string, string>>
     */
    private function buildDefaultLocalEngine(): array
    {
        $class = $this->findWordPressAdapter();

        if ($class === null) {
            return [];
        }

        $name = function_exists('__') ? __('Local Website Search', 'rrze-search') : 'Local Website Search';
        $desc = function_exists('__') ? __('Native WordPress search results', 'rrze-search') : 'Native WordPress search results';

        return [[
            'name'           => $name,
            'desc'           => $desc,
            'cx'             => '',
            'key'            => '',
            'resource_class' => $class,
            'enabled'        => true,
        ]];
    }

    /**
     * Locate the adapter class that wraps the WordPress search foundation.
     */
    private function findWordPressAdapter(): ?string
    {
        foreach ($this->adapterCollection as $class => $meta) {
            if (!is_string($class)) {
                continue;
            }

            if (str_contains(strtolower($class), 'wordpressadapter')) {
                return $class;
            }
        }

        return null;
    }

    /**
     * Remove duplicate presets so each adapter class appears at most once.
     * Priorität: "Global" > "Default Local" > "anderes gleichwertig".
     * Bei Gleichstand entscheidet die höhere Vollständigkeit (Score), sonst das zuerst gesehene.
     *
     * @param array<int, array<string, mixed>> $presets
     * @return array<int, array<string, mixed>>
     */
    private function dedupePresetsByClass(array $presets): array
    {
        $bestByClass = [];        // class => ['preset' => array, 'isLocalDefault' => bool, 'score' => int, 'firstIdx' => int]
        $passthrough = [];        // Presets ohne ermittelbare Klasse werden unverändert durchgereicht

        foreach ($presets as $idx => $preset) {
            if (!is_array($preset)) {
                continue;
            }

            // Klasse bestimmen (robust, inkl. Mapping auf WP-Adapter via Name-Heuristik)
            $class = $preset['resource_class'] ?? $preset['class'] ?? null;
            if (is_string($class)) {
                $class = sanitize_key($class);
            }

            // Heuristik für die lokale WP-Suche, falls keine Klasse vorhanden ist
            if ((!$class || !is_string($class) || $class === '') && isset($preset['name']) && is_string($preset['name'])) {
                $nameCandidate     = strtolower($preset['name']);
                $localNameVariants = ['local website search', 'native wordpress search', 'wordpress'];
                if (function_exists('__')) {
                    $localNameVariants[] = strtolower(__('Local Website Search', 'rrze-search'));
                    $localNameVariants[] = strtolower(__('Native WordPress search results', 'rrze-search'));
                }
                if (in_array($nameCandidate, $localNameVariants, true)) {
                    $mapped = $this->findWordPressAdapter();
                    if (is_string($mapped) && $mapped !== '') {
                        $class = sanitize_key($mapped);
                        $preset['resource_class'] = $class;
                    }
                }
            }

            // Keine Klasse ermittelbar -> nicht deduplizieren, hinten anhängen
            if (!$class || !is_string($class) || $class === '') {
                $passthrough[] = $preset;
                continue;
            }

            // Für die Priorisierungslogik vorbereiten
            $isLocalDefault = $this->isLocalDefaultPreset($preset);
            $score          = $this->completenessScore($preset);

            if (!isset($bestByClass[$class])) {
                // Erstes Vorkommen dieser Klasse
                $preset['resource_class'] = $class; // sicherstellen
                $bestByClass[$class] = [
                    'preset'         => $preset,
                    'isLocalDefault' => $isLocalDefault,
                    'score'          => $score,
                    'firstIdx'       => $idx,
                ];
                continue;
            }

            // Es existiert bereits ein Kandidat für diese Klasse -> Auswahl treffen
            $current = $bestByClass[$class];

            // 1) Global > LocalDefault
            if ($current['isLocalDefault'] && !$isLocalDefault) {
                // neues (globales/„nicht Default-Local“) Preset gewinnt
                $preset['resource_class'] = $class;
                $bestByClass[$class] = [
                    'preset'         => $preset,
                    'isLocalDefault' => $isLocalDefault,
                    'score'          => $score,
                    'firstIdx'       => $current['firstIdx'], // Ordnung am ersten Auftreten ausrichten
                ];
                continue;
            }
            if (!$current['isLocalDefault'] && $isLocalDefault) {
                // bestehender ist global, neuer ist default-local -> ignorieren
                continue;
            }

            // 2) Beide gleiche „Globalität“ -> Vollständigkeit vergleichen
            if ($score > $current['score']) {
                $preset['resource_class'] = $class;
                $bestByClass[$class] = [
                    'preset'         => $preset,
                    'isLocalDefault' => $isLocalDefault,
                    'score'          => $score,
                    'firstIdx'       => $current['firstIdx'],
                ];
                continue;
            }

            // 3) Bei Gleichstand behalten wir den zuerst gesehenen (stabile Ausgabe)
            // -> nichts tun
        }

        // Ausgabe: nach erstem Auftreten pro Klasse sortieren
        uasort($bestByClass, static function ($a, $b) {
            return $a['firstIdx'] <=> $b['firstIdx'];
        });

        $unique = [];
        foreach ($bestByClass as $bundle) {
            $unique[] = $bundle['preset'];
        }

        // Presets ohne Klasse hinten anhängen
        foreach ($passthrough as $p) {
            $unique[] = $p;
        }

        return $unique;
    }

    /**
     * Erkennung des Default-Local-Presets (aus buildDefaultLocalEngine()).
     * Heuristik: Name/Desc entsprechen dem Default (inkl. Übersetzungen) und beide Tokens (cx/key) leer.
     */
    private function isLocalDefaultPreset(array $preset): bool
    {
        $name = isset($preset['name']) && is_string($preset['name']) ? strtolower($preset['name']) : '';
        $desc = isset($preset['desc']) && is_string($preset['desc']) ? strtolower($preset['desc']) : '';
        $cx   = isset($preset['cx']) && is_string($preset['cx']) ? trim($preset['cx']) : '';
        // key | api | apikey sind alternative Felder
        $api  = '';
        if (isset($preset['key']) && is_string($preset['key'])) {
            $api = trim($preset['key']);
        } elseif (isset($preset['api']) && is_string($preset['api'])) {
            $api = trim($preset['api']);
        } elseif (isset($preset['apikey']) && is_string($preset['apikey'])) {
            $api = trim($preset['apikey']);
        }

        $localName = 'local website search';
        $localDesc = 'native wordpress search results';
        if (function_exists('__')) {
            $localName = strtolower(__('Local Website Search', 'rrze-search'));
            $localDesc = strtolower(__('Native WordPress search results', 'rrze-search'));
        }

        $nameMatches = ($name === $localName);
        $descMatches = ($desc === $localDesc);
        $tokensEmpty = ($cx === '' && $api === '');

        return $nameMatches && $descMatches && $tokensEmpty;
    }

    /**
     * Bewertet, wie „vollständig“ ein Preset ist.
     * Höherer Score bevorzugt (mehr sinnvolle Felder befüllt).
     */
    private function completenessScore(array $preset): int
    {
        $score = 0;

        // Schlüssel-Felder
        $score += (!empty($preset['cx']) && is_string($preset['cx'])) ? 2 : 0;

        $apiFilled = false;
        foreach (['key', 'api', 'apikey'] as $k) {
            if (isset($preset[$k]) && is_string($preset[$k]) && $preset[$k] !== '') {
                $apiFilled = true; break;
            }
        }
        $score += $apiFilled ? 2 : 0;

        // Metadaten
        $score += (isset($preset['name']) && is_string($preset['name']) && $preset['name'] !== '') ? 1 : 0;
        $score += (isset($preset['desc']) && is_string($preset['desc']) && $preset['desc'] !== '') ? 1 : 0;

        // Klasse vorhanden gibt Bonus (robustheit)
        $score += (isset($preset['resource_class']) && is_string($preset['resource_class']) && $preset['resource_class'] !== '') ? 1 : 0;

        return $score;
    }

}
