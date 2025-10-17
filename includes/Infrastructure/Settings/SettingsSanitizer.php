<?php

namespace RRZE\RRZESearch\Infrastructure\Settings;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Application\Controller\AppController;
use RRZE\RRZESearch\Infrastructure\Helper\Helper;

/**
 * Sanitizes and normalizes RRZE Search settings prior to persistence.
 *
 * Keeps resource and engine collections consistent by filling defaults,
 * pruning stale records, and syncing metadata such as labels and classes.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class SettingsSanitizer extends AppController
{
    /**
     * Sanitizes the submitted settings payload from the RRZE Search dashboard.
     *
     * Ensures all resources have display names, keeps engine metadata aligned
     * with their resource counterparts, and removes stale or empty entries.
     *
     * @param array<string, mixed> $input Raw settings submitted from the form.
     *
     * @return array<string, mixed> Cleaned settings ready for persistence.
     */
    public function sanitize(array $input): array
    {
        $name   = 'rrze_search_settings';
        $option = get_option($name);
        $output = [];

        // Configured Search Engines - Super Admin Level
        $existingResources = isset($option['rrze_search_resources']) && is_array($option['rrze_search_resources'])
            ? $option['rrze_search_resources']
            : [];
        $existingEngines = isset($option['rrze_search_engines']) && is_array($option['rrze_search_engines'])
            ? $option['rrze_search_engines']
            : [];

        $output['rrze_search_resources'] = $input['rrze_search_resources'] ?? $existingResources;
        foreach ($output['rrze_search_resources'] as $key => $resource) {
            if (!isset($output['rrze_search_resources'][$key]['enabled'])) {
                $output['rrze_search_resources'][$key]['enabled'] = true;
            }
            if ($output['rrze_search_resources'][$key]['resource_name'] === ''){
                $output['rrze_search_resources'][$key]['resource_name'] = $this->enginesClassCollection[$resource['resource_class']]['label'];
            }
        }

        // Installed Search Engines - Regular Admin Level
        $output['rrze_search_engines'] = $input['rrze_search_engines'] ?? $existingEngines;
//        foreach ($output['rrze_search_resources'] as $key => $resource) {
//            $output['rrze_search_engines'][$key]['resource_name'] = $this->enginesClassCollection[$resource['resource_class']]['label'];
//        }

        // Persist the page ID used to surface search results.
        $output['rrze_search_page_id'] = $input['rrze_search_page_id'] ?? ($option['rrze_search_page_id'] ?? 0);

        // Sanitize the engine collection to mirror the resource configuration.
        if (!empty($output['rrze_search_resources'])) {
            $engineCollectionUpdate = [];

            // Collection of Engine Ids
            $engineIds = [];
            foreach ($existingEngines as $engineOption) {
                $engineIds[] = $engineOption['resource_id'];
            }

            // Collection of Resource Ids
            $resourceIds = [];
            foreach ($output['rrze_search_resources'] as $resourceOption) {
                $resourceIds[] = $resourceOption['resource_id'];
            }

            // Add Resources which don't exist in the Engine Collection
            foreach ($output['rrze_search_resources'] as $key => $resource) {
                if (in_array($resource['resource_id'], $engineIds)) {
                    // include the engine into our update collection
                    $engineCollectionUpdate[] = Helper::getEngineById('rrze_search_settings', $resource['resource_id']);
                } else {
                    // create an engine for our update collection, will likely create empty record which will removed
                    $engine                   = [
                        'resource_id'         => $resource['resource_id'],
                        'resource_disclaimer' => $resource['resource_disclaimer'] ?? '',
                        'enabled'             => true,
                    ];
                    $engine['resource_name']  = $this->enginesClassCollection[$resource['resource_class']]['label'];
                    $engine['resource_class'] = $resource['resource_class'];
                    $engineCollectionUpdate[] = $engine;
                }
            }

            // Update Labels
            foreach ($output['rrze_search_engines'] as $key => $engine) {
                if (isset($engine['enabled'])) {
                    $engineCollectionUpdate[$key]['enabled'] = true;
                } else {
                    unset($engineCollectionUpdate[$key]['enabled']);
                }

                if($output['rrze_search_resources'][$key]['resource_name'] !== '') {
                    $engineCollectionUpdate[$key]['resource_name']  = $output['rrze_search_resources'][$key]['resource_name'];
                } else {
                    $engineCollectionUpdate[$key]['resource_name']  = $this->enginesClassCollection[$output['rrze_search_resources'][$key]['resource_class']]['label'];
                }
                $engineCollectionUpdate[$key]['resource_class'] = $output['rrze_search_resources'][$key]['resource_class'];

                // Automatically disable the engine when Class changed
                if ($engine['resource_class'] !== $output['rrze_search_resources'][$key]['resource_class']) {
                    unset($engineCollectionUpdate[$key]['enabled']);
                }
            }

            // Remove those empty entries described in line 68
            foreach ($engineCollectionUpdate as $key => $engine) {
                if (empty($engine['resource_class']) && empty($engine['resource_name'])) {
                    unset($engineCollectionUpdate[$key]);
                }
            }

            // Remove engines that don't exist in our current engineId collection
            foreach ($existingEngines as $key => $engine) {
                if (!in_array($engine['resource_id'], $engineIds)) {
                    unset($engineCollectionUpdate[$key]);
                }
            }
            
            $output['rrze_search_engines'] = $engineCollectionUpdate;
        }

        return $output;
    }
}
