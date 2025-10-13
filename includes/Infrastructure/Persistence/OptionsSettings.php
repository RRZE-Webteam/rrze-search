<?php

namespace RRZE\RRZESearch\Infrastructure\Persistence;

use RRZE\RRZESearch\Application\Controller\AppController;
use RRZE\RRZESearch\Infrastructure\Helper\Helper;

/**
 * Options Sanitize
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class OptionsSettings extends AppController
{
    /**
     * Sanitize Options Submitted
     *
     * @param array $input Submitted options
     *
     * @return array Sanitized options
     */
    public function sanitize($input): array
    {
        $name   = 'rrze_search_settings';
        $option = get_option($name);
        $output = [];

        // Configured Search Engines - Super Admin Level
        $output['rrze_search_resources'] = ($input['rrze_search_resources']) ? $input['rrze_search_resources'] : $option['rrze_search_resources'];
        foreach ($output['rrze_search_resources'] as $key => $resource) {
            if ($output['rrze_search_resources'][$key]['resource_name'] === ''){
                $output['rrze_search_resources'][$key]['resource_name'] = $this->enginesClassCollection[$resource['resource_class']]['label'];
            }
        }

        // Installed Search Engines - Regular Admin Level
        $output['rrze_search_engines'] = ($input['rrze_search_engines']) ? $input['rrze_search_engines'] : $option['rrze_search_engines'];
//        foreach ($output['rrze_search_resources'] as $key => $resource) {
//            $output['rrze_search_engines'][$key]['resource_name'] = $this->enginesClassCollection[$resource['resource_class']]['label'];
//        }

        /** Page ID for Search Results */
        $output['rrze_search_page_id'] = ($input['rrze_search_page_id']) ? $input['rrze_search_page_id'] : $option['rrze_search_page_id'];

        /**
         * Sanitize $option['rrze_search_engines']
         */
        if ($input['rrze_search_resources']) {
            $engineCollectionUpdate = [];

            // Collection of Engine Ids
            $engineIds = [];
            foreach ($option['rrze_search_engines'] as $engineOption) {
                $engineIds[] = $engineOption['resource_id'];
            }

            // Collection of Resource Ids
            $resourceIds = [];
            foreach ($input['rrze_search_resources'] as $resourceOption) {
                $resourceIds[] = $resourceOption['resource_id'];
            }

            // Add Resources which don't exist in the Engine Collection
            foreach ($input['rrze_search_resources'] as $key => $resource) {
                if (in_array($resource['resource_id'], $engineIds)) {
                    // include the engine into our update collection
                    $engineCollectionUpdate[] = Helper::getEngineById('rrze_search_settings', $resource['resource_id']);
                } else {
                    // create an engine for our update collection, will likely create empty record which will removed
                    $engine                   = [
                        'resource_id'         => $resource['resource_id'],
                        'resource_disclaimer' => $resource['resource_disclaimer'],
                    ];
                    $engine['resource_name']  = $this->enginesClassCollection[$resource['resource_class']]['label'];
                    $engine['resource_class'] = $resource['resource_class'];
                    $engineCollectionUpdate[] = $engine;
                }
            }

            // Update Labels
            foreach ($option['rrze_search_engines'] as $key => $engine) {
                if($input['rrze_search_resources'][$key]['resource_name'] !== '') {
                    $engineCollectionUpdate[$key]['resource_name']  = $input['rrze_search_resources'][$key]['resource_name'];
                } else {
                    $engineCollectionUpdate[$key]['resource_name']  = $this->enginesClassCollection[$input['rrze_search_resources'][$key]['resource_class']]['label'];
                }
                $engineCollectionUpdate[$key]['resource_class'] = $input['rrze_search_resources'][$key]['resource_class'];

                // Automatically disable the engine when Class changed
                if ($engine['resource_class'] !== $input['rrze_search_resources'][$key]['resource_class']) {
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
            foreach ($option['rrze_search_engines'] as $key => $engine) {
                if (!in_array($engine['resource_id'], $engineIds)) {
                    unset($engineCollectionUpdate[$key]);
                }
            }

            $output['rrze_search_engines'] = 'empty';
            $output['rrze_search_engines'] = $engineCollectionUpdate;
        }

        return $output;
    }
}