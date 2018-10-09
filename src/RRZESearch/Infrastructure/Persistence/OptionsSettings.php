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
            $output['rrze_search_resources'][$key]['resource_name'] = $this->enginesClassCollection[$resource['resource_class']]['label'];
        }

        // Installed Search Engines - Regular Admin Level
//        $output['rrze_search_engines'] = ($input['rrze_search_engines']) ? $input['rrze_search_engines'] : $option['rrze_search_engines'];
//        foreach ($output['rrze_search_resources'] as $key => $resource) {
//            $output['rrze_search_engines'][$key]['resource_name'] = $this->enginesClassCollection[$resource['resource_class']]['label'];
//        }

        /** Page ID for Search Results */
        $output['rrze_search_page_id'] = ($input['rrze_search_page_id']) ? $input['rrze_search_page_id'] : $option['rrze_search_page_id'];

        /**
         * Sanitize $option['rrze_search_engines']
         *
         * 1. Remove Engines that have been removed from Resource Collection
         * 2. Add Resource which do not exist in Engine Collection
         * 3. Update Labels
         */
        if ($input['rrze_search_resources']) {
            $engineCollectionUpdate = [];

            $engineIds = [];
            foreach ($option['rrze_search_engines'] as $engineOption) {
                $engineIds[] = $engineOption['resource_id'];
            }

            $resourceIds = [];
            foreach ($option['rrze_search_resources'] as $resourceOption) {
                $resourceIds[] = $resourceOption['resource_id'];
            }

            // Step 1 -> Remove engines that are no longer in the Resource collection
//            foreach ($option['rrze_search_engines'] as $key => $engineEntry) {
//                if (!in_array($engineEntry['resource_id'], $engineIds)){
//                    unset($option['rrze_search_engines'][$key]);
//                }
//            }

            // Step 2 -> Add Resources which don't exist in the Engine Collection
//            foreach ($option['rrze_search_resources'] as $resource) {
//                if (!in_array($resource['resource_id'], $engineIds)) {
//                    $engineCollectionUpdate[] = $resource;
//                }
//            }

            foreach ($option['rrze_search_resources'] as $resource) {

                if (Helper::isResourceEngine('rrze_search_engines', $resource['resource_id'])){
                    $engineCollectionUpdate[] = $resource;
                }

//                if (empty($engine)) {
//                    $engine                   = [
//                        'resource_id'         => $resource['resource_id'],
//                        'resource_disclaimer' => $resource['resource_disclaimer'],
//                        'enabled'             => false
//                    ];
//                    $engine['resource_name']  = $resource['resource_name'];
//                    $engine['resource_class'] = $resource['resource_class'];
//                    $engineCollectionUpdate[] = $engine;
//                }
            }
            $output['rrze_search_engines'] = 'empty';
            $output['rrze_search_engines'] = $engineCollectionUpdate;
        }

        return $output;
    }
}