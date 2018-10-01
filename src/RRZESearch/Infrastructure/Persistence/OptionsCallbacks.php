<?php

namespace RRZE\RRZESearch\Infrastructure\Persistence;

use RRZE\RRZESearch\Application\Controller\AppController;
use RRZE\RRZESearch\Infrastructure\Helper\Helper;

class OptionsCallbacks extends AppController
{
    /**
     * Registered search engines
     *
     * @var array
     */
    protected $engines = [];

    /**
     * @var string
     */
    private $facades_dir;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * Define Search Engines
         *
         * Usage: $this->engines
         */
        $adapterDirectory = \dirname(__DIR__,
                2).DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Engines'.DIRECTORY_SEPARATOR.'Adapters';
        foreach (scandir($adapterDirectory, SCANDIR_SORT_NONE) as $adapterFile) {
            if ($adapterFile !== '.' && $adapterFile !== '..') {
                $engineName                      = pathinfo($adapterFile, PATHINFO_FILENAME);
                $engineClassName                 = 'RRZE\\RRZESearch\\Ports\\Engines\\Adapters\\'.$engineName;
                $this->engines[$engineClassName] = [
                    'name'       => \call_user_func([$engineClassName, 'getName']),
                    'label'      => \call_user_func([$engineClassName, 'getLabel']),
                    'link_label' => \call_user_func([$engineClassName, 'getLinkLabel'])
                ];
            }
        }

        /**
         * Shortcut to Facades
         */
        $this->facades_dir = $this->plugin_path.implode(DIRECTORY_SEPARATOR, ['RRZESearch', 'Ports', 'Facades']);
    }

    /**
     * Sanitize Options Submitted
     *
     * @param $input
     *
     * @return array
     */
    public function sanitize($input): array
    {
        $option = get_option('rrze_search_settings');
        $output = array();

        /** Configured Search Engines - Super Admin Level */
        $output['rrze_search_resources'] = ($input['rrze_search_resources']) ? $input['rrze_search_resources'] : $option['rrze_search_resources'];
        foreach ($output['rrze_search_resources'] as $key => $resource) {
            $output['rrze_search_resources'][$key]['resource_name'] = $this->engines[$resource['resource_class']]['label'];
        }

        /** Installed Search Engines - Regular Admin Level */
        $output['rrze_search_engines'] = ($input['rrze_search_engines']) ? $input['rrze_search_engines'] : $option['rrze_search_engines'];
        foreach ($output['rrze_search_resources'] as $key => $resource) {
            $output['rrze_search_engines'][$key]['resource_name'] = $this->engines[$resource['resource_class']]['label'];
        }

        /** Page ID for Search Results */
        $output['rrze_search_page_id'] = ($input['rrze_search_page_id']) ? $input['rrze_search_page_id'] : $option['rrze_search_page_id'];

        /** Custom Field value for pages tagged as disclaimer pages */
        $output['meta_shortcut'] = 'rrze_search_resource_disclaimer';

        return $output;
    }

    public function printAdminSection(): void
    {
        echo __('Enable Search Engines', 'rrze-search');
    }

    public function printSuperAdminSection(): void
    {
        echo __('Configure Search Engines', 'rrze-search');
    }

    /**
     * Render the engines table - Admin
     * Facade: admin-engine-toggle.php
     *
     * @param array $args
     */
    public function enginesToggle(array $args): void
    {
        $name         = $args['label_for'];
        $option_name  = $args['option_name'];
        $option_value = get_option($option_name);

        /**
         * Define props used in template
         */
        $engines = $option_value[$name];

        /** Clean up the array */
        foreach ($engines as $key => $engine) {
            if (!isset($engine['resource_id'])) {
                unset($engines[$key]);
            }
        }

        /** Add new Resources from Engine Collection */
        foreach ($option_value['rrze_search_resources'] as $resource) {
            if (!Helper::isResourceEngine($option_name, $resource['resource_id'])) {
                $engines[] = [
                    'resource_id'         => $resource['resource_id'],
                    'resource_name'       => $resource['resource_name'],
                    'resource_class'      => $resource['resource_class'],
                    'resource_disclaimer' => $resource['resource_disclaimer'],
                    'enabled'             => false
                ];
            }
        }

        /** Remove old Engines missing from Resources Collection */
        $nextResourceIndex = 0;
        foreach ($engines as $engine) {
            if (!Helper::isEngineResource($option_name, $engine['resource_id'])) {
                array_splice($engines, $nextResourceIndex - 1, 1);
            }
            $nextResourceIndex++;
        }

        /** Engine table */
        $engines = array_values($engines);
        require $this->facades_dir.DIRECTORY_SEPARATOR.'admin-engine-toggle.php';
    }

    /**
     * Render the resources table - Super Admin
     * Facade: admin-engine-configuration.php
     *
     * @param array $args Arguments
     */
    public function enginesConfigure(array $args): void
    {
        $name         = $args['label_for'];
        $option_name  = $args['option_name'];
        $option_value = get_option($option_name);

        /**
         * Define props used in template
         */
        $resources = $option_value[$name];

        /** Resource table */
        require $this->facades_dir.DIRECTORY_SEPARATOR.'admin-engine-configuration.php';

        /** Resource template */
        require $this->facades_dir.DIRECTORY_SEPARATOR.'admin-engine-template.php';

    }

    /**
     * Renders disabled Input field with Search Results page name
     * Facade: admin-results-page-input.php
     *
     * @param array $args Arguments
     */
    public function resultsPage($args): void
    {
        $name          = $args['label_for'];
        $option_name   = $args['option_name'];
        $options_value = get_option($option_name);

        if (array_key_exists($name, $options_value)) {
            /**
             * Test the Permalink to ensure current user isn't overwriting post created by another user
             */
            if ($options_value[$name] === '' || !get_permalink($options_value[$name])) {

                $rrze_search_page     = array(
                    'post_date'     => date('Y-m-d H:i:s'),
                    'post_date_gmt' => date('Y-m-d H:i:s'),
                    'post_content'  => '[rrze_search_results]',
                    'post_name'     => 'rrze_search_page',
                    'post_title'    => __('RRZE Search Results', 'rrze-search'),
                    'post_status'   => 'publish',
                    'post_type'     => 'page',
                    'post_excerpt'  => __('Search Result Page utilized by RRZE Search Plugin', 'rrze-search'),
                );
                $rrze_search_page_id  = wp_insert_post($rrze_search_page);
                $options_value[$name] = $rrze_search_page_id;
                update_option($option_name, $options_value, true);
            }


            if (get_post($options_value[$name])) {
                require $this->facades_dir.DIRECTORY_SEPARATOR.'admin-results-page-input.php';
            } else {
                echo __('Oh no! Someone deleted the results Page! No worries, Another one will be generated when you click [ Save Changes ]',
                    'rrze-search');
            }
        } else {
            echo __('Search Results Page doesn\'t exist, yet! No worries, one will be generated when you click [ Save Changes ]',
                'rrze-search');
        }
    }
}