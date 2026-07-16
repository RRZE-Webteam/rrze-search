<?php
declare(strict_types=1);

namespace RRZE\RRZESearch\Infrastructure\Settings;

defined('ABSPATH') || exit;

use RRZE\RRZESearch\Application\Controller\AppController;
use RRZE\RRZESearch\Infrastructure\ResultsPage;

/**
 * Sanitizes and normalizes RRZE Search settings prior to persistence.
 *
 * Synchronizes the configured resources (Super Admin) with the installed engines (Admin),
 * fills defaults, enforces casting/sanitization, and removes stale/invalid entries.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
final class SettingsSanitizer extends AppController
{
    /**
     * Sanitize settings payload from the RRZE Search dashboard.
     *
     * @param array<string,mixed> $input Raw settings submitted from the form (already unslashed by WP core in most cases).
     * @return array<string,mixed> Cleaned settings ready for persistence.
     */
    public function sanitize(array $input): array
    {
        $optionName = 'rrze_search_settings';
        $option     = get_option($optionName);

        $existingResources = (isset($option['rrze_search_resources']) && is_array($option['rrze_search_resources']))
            ? $option['rrze_search_resources']
            : [];

        $existingEngines = (isset($option['rrze_search_engines']) && is_array($option['rrze_search_engines']))
            ? $option['rrze_search_engines']
            : [];

        // 1) Quellen bestimmen (Input bevorzugt, sonst Bestand)
        $rawResources = isset($input['rrze_search_resources']) && is_array($input['rrze_search_resources'])
            ? $input['rrze_search_resources']
            : $existingResources;

        $rawEnginesInput = isset($input['rrze_search_engines']) && is_array($input['rrze_search_engines'])
            ? $input['rrze_search_engines']
            : [];

        // 2) Ressourcen normalisieren/sanitizen → Map nach resource_id
        $resourcesById = [];
        foreach ((array) $rawResources as $rawResource) {
            if (!is_array($rawResource)) {
                continue;
            }

            $resourceId = $this->normalizeResourceId($rawResource['resource_id'] ?? null);
            if ($resourceId === '') {
                continue; // Ungültig -> überspringen
            }

            $resourceClass = isset($rawResource['resource_class']) ? sanitize_text_field((string) $rawResource['resource_class']) : '';
            $resourceName  = isset($rawResource['resource_name']) ? sanitize_text_field((string) $rawResource['resource_name']) : '';
            $resourceDisclaimer = isset($rawResource['resource_disclaimer']) ? wp_kses_post((string) $rawResource['resource_disclaimer']) : '';
            $isResourceEnabledNetworkWide = !empty($rawResource['enabled']);

            if ($resourceName === '') {
                $resourceName = $this->lookupClassLabel($resourceClass);
            }

            // Wenn weder Klasse noch Name vorhanden, ignorieren
            if ($resourceClass === '' && $resourceName === '') {
                continue;
            }

            $resourcesById[$resourceId] = [
                'resource_id'         => $resourceId,
                'resource_class'      => $resourceClass,
                'resource_name'       => $resourceName,
                'resource_disclaimer' => $resourceDisclaimer,
                'enabled'             => $isResourceEnabledNetworkWide,
            ];
        }

        // 3) Bestehende Engines nach ID indizieren
        $existingEnginesById = [];
        foreach ((array) $existingEngines as $existingEngineEntry) {
            if (!is_array($existingEngineEntry)) {
                continue;
            }

            $existingEngineResourceId = $this->normalizeResourceId($existingEngineEntry['resource_id'] ?? null);
            if ($existingEngineResourceId === '') {
                continue;
            }
            $existingEnginesById[$existingEngineResourceId] = $existingEngineEntry;
        }

        // 4) Input-Engines (Admin-Ebene) einlesen → Flags/Overrides aus Formular
        //    Wir lesen v. a. "enabled" und optionale Felder pro Engine.
        $inputEnginesById = [];
        foreach ((array) $rawEnginesInput as $rawEngineInput) {
            if (!is_array($rawEngineInput)) {
                continue;
            }

            $inputEngineResourceId = $this->normalizeResourceId($rawEngineInput['resource_id'] ?? null);
            if ($inputEngineResourceId === '') {
                continue;
            }
            $inputEnginesById[$inputEngineResourceId] = [
                'enabled'             => !empty($rawEngineInput['enabled']),
                // Erlaube optionale Feld-Overrides aus Engine-Form:
                'resource_disclaimer' => isset($rawEngineInput['resource_disclaimer']) ? wp_kses_post((string) $rawEngineInput['resource_disclaimer']) : null,
                'resource_name'       => isset($rawEngineInput['resource_name']) ? sanitize_text_field((string) $rawEngineInput['resource_name']) : null,
                'resource_class'      => isset($rawEngineInput['resource_class']) ? sanitize_text_field((string) $rawEngineInput['resource_class']) : null,
            ];
        }

        // 5) Engines aus Ressourcen aufbauen/synchronisieren
        $enginesOut = [];

        foreach ($resourcesById as $resourceId => $resource) {
            $resourceClass      = $resource['resource_class'];
            $resourceName       = $resource['resource_name'];
            $resourceDisclaimer = $resource['resource_disclaimer'];
            $isResourceEnabledNetworkWide  = (bool) $resource['enabled'];

            $existingEngine     = $existingEnginesById[$resourceId] ?? [];
            $existingClass      = isset($existingEngine['resource_class']) ? sanitize_text_field((string) $existingEngine['resource_class']) : '';
            $existingName       = isset($existingEngine['resource_name']) ? sanitize_text_field((string) $existingEngine['resource_name']) : '';
            $existingDisclaimer = isset($existingEngine['resource_disclaimer']) ? wp_kses_post((string) $existingEngine['resource_disclaimer']) : '';
            $existingEnabled    = !empty($existingEngine['enabled']);

            $inputEngine        = $inputEnginesById[$resourceId] ?? null;
            $inputEnabled       = $inputEngine['enabled'] ?? null;

            // Quelle für Disclaimer/Name bestimmen: Engine-Input -> Resource -> Existing
            $engineDisclaimer = isset($inputEngine['resource_disclaimer']) && $inputEngine['resource_disclaimer'] !== null
                ? (string) $inputEngine['resource_disclaimer']
                : ($resourceDisclaimer !== '' ? $resourceDisclaimer : $existingDisclaimer);

            $engineName = isset($inputEngine['resource_name']) && $inputEngine['resource_name'] !== null
                ? (string) $inputEngine['resource_name']
                : ($resourceName !== '' ? $resourceName : ($existingName !== '' ? $existingName : $this->lookupClassLabel($resourceClass)));

            // Klasse final festlegen (Resource führt)
            $engineClass = $resourceClass !== '' ? $resourceClass
                : ((isset($inputEngine['resource_class']) && $inputEngine['resource_class'] !== null) ? (string) $inputEngine['resource_class'] : $existingClass);

            // Enabled-Logik:
            // - Super-Admin muss Ressource freigeben
            // - Admin kann Engine aktivieren/deaktivieren (Input-Flag)
            // - Klassenwechsel führt zu Auto-Disable
            $classChanged = ($existingClass !== '') && ($existingClass !== $engineClass);
            $enabled      = false;

            if ($isResourceEnabledNetworkWide) {
                if ($classChanged) {
                    $enabled = false; // Auto-Disable bei Klassenwechsel
                } else {
                    // Admin-Entscheidung, sonst bestehender Zustand, sonst Default true
                    if ($inputEnabled !== null) {
                        $enabled = (bool) $inputEnabled;
                    } elseif ($existingEngine !== []) {
                        $enabled = (bool) $existingEnabled;
                    } else {
                        $enabled = true;
                    }
                }
            } // else: Ressource nicht freigegeben -> Engine bleibt disabled

            // Leere/ungültige Datensätze aussortieren
            if ($engineClass === '' && $engineName === '') {
                continue;
            }

            $enginesOut[] = [
                'resource_id'         => $resourceId,
                'resource_class'      => $engineClass,
                'resource_name'       => $engineName,
                'resource_disclaimer' => $engineDisclaimer,
                'enabled'             => $enabled ? true : false,
            ];
        }

        // 6) Standard-Suchmaschine bestimmen
        $preferredDefault = '';
        if (isset($input['rrze_search_default_engine'])) {
            $preferredDefault = sanitize_text_field((string) $input['rrze_search_default_engine']);
        } elseif (isset($option['rrze_search_default_engine'])) {
            $preferredDefault = sanitize_text_field((string) $option['rrze_search_default_engine']);
        }

        $enabledEnginesByResourceId = [];
        $allEnginesByResourceId = [];
        $wordpressResourceId = null;

        foreach ($enginesOut as $engineEntry) {
            $engineResourceId = isset($engineEntry['resource_id']) ? (string) $engineEntry['resource_id'] : '';
            if ($engineResourceId === '') {
                continue;
            }

            $allEnginesByResourceId[$engineResourceId] = $engineEntry;

            if (!empty($engineEntry['enabled'])) {
                $enabledEnginesByResourceId[$engineResourceId] = $engineEntry;
            }

            if ($wordpressResourceId === null && isset($engineEntry['resource_class']) && $this->isWordPressAdapterClass((string) $engineEntry['resource_class'])) {
                $wordpressResourceId = $engineResourceId;
            }
        }

        $defaultEngine = '';
        if ($preferredDefault !== '' && isset($enabledEnginesByResourceId[$preferredDefault])) {
            $defaultEngine = $preferredDefault;
        } elseif ($wordpressResourceId !== null && isset($enabledEnginesByResourceId[$wordpressResourceId])) {
            $defaultEngine = $wordpressResourceId;
        } elseif (!empty($enabledEnginesByResourceId)) {
            $enabledKeys = array_keys($enabledEnginesByResourceId);
            $firstKey = reset($enabledKeys);
            if ($firstKey !== false) {
                $defaultEngine = (string) $firstKey;
            }
        } elseif ($wordpressResourceId !== null && isset($allEnginesByResourceId[$wordpressResourceId])) {
            $defaultEngine = $wordpressResourceId;
        }

        // 7) Die verwaltete Systemseite darf nicht über Formulardaten
        //    einer beliebigen anderen Seite zugeordnet werden.
        $pageId = ResultsPage::getId();

        // 8) Finale Ressourcenliste für Ausgabe (normiert, numerische Indizes)
        $resourcesOut = array_values($resourcesById);

        // 9) Ergebnis zusammenstellen
        return [
            'rrze_search_resources' => $resourcesOut,
            'rrze_search_engines'   => array_values($enginesOut),
            'rrze_search_page_id'   => $pageId,
            'rrze_search_default_engine' => $defaultEngine,
        ];
    }

    /**
     * Safely fetch a human-readable label for a given engine class.
     *
     * @param string $class
     * @return string
     */
    private function lookupClassLabel(string $class): string
    {
        if ($class === '') {
            return '';
        }

        // $this->enginesClassCollection wird in AppController bereitgestellt.
        if (
            isset($this->enginesClassCollection[$class]) &&
            is_array($this->enginesClassCollection[$class]) &&
            isset($this->enginesClassCollection[$class]['label'])
        ) {
            $label = (string) $this->enginesClassCollection[$class]['label'];
            return sanitize_text_field($label);
        }

        return '';
    }

    private function normalizeResourceId(mixed $resourceId): string
    {
        if ($resourceId === null) {
            return '';
        }

        return trim(sanitize_text_field((string) $resourceId));
    }

    private function isWordPressAdapterClass(string $class): bool
    {
        $class = strtolower(trim($class));
        return $class !== '' && str_contains($class, 'wordpressadapter');
    }
}
