<?php

namespace RRZE\RRZESearch\Infrastructure\Persistence;

use RRZE\RRZESearch\Application\Controller\AppController;
use RRZE\RRZESearch\Infrastructure\Helper\Helper;

/**
 * Options callback
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class OptionsCallbacks extends AppController
{
    /**
     * Registered search engines
     *
     * @var array[]
     */
    protected $engines = [];

    /**
     * Templates directory
     *
     * @var string
     */
    protected $templatesDir;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Define search engines
        $adapterDirectory = \dirname(__DIR__,
                2).DIRECTORY_SEPARATOR.'Infrastructure'.DIRECTORY_SEPARATOR.'Engines'.DIRECTORY_SEPARATOR.'Adapters';
        foreach (scandir($adapterDirectory, SCANDIR_SORT_NONE) as $adapterFile) {
            if ($adapterFile !== '.' && $adapterFile !== '..') {
                $engineName                      = pathinfo($adapterFile, PATHINFO_FILENAME);
                $engineClassName                 = 'RRZE\\RRZESearch\\Infrastructure\\Engines\\Adapters\\'.$engineName;
                $this->engines[$engineClassName] = [
                    'name'       => \call_user_func([$engineClassName, 'getName']),
                    'label'      => \call_user_func([$engineClassName, 'getLabel']),
                    'link_label' => \call_user_func([$engineClassName, 'getLinkLabel'])
                ];
            }
        }

        // Shortcut to template directory
        $this->templatesDir = $this->pluginPath.implode(DIRECTORY_SEPARATOR,
                ['RRZESearch', 'Infrastructure', 'Templates']);
    }

    /**
     * Sanitize Options Submitted
     *
     * @param array $input Submitted options
     *
     * @return array Sanitized options
     */
    public function sanitize($input): array
    {
        $option = get_option('rrze_search_settings');
        $output = [];

        // Configured Search Engines - Super Admin Level
        $output['rrze_search_resources'] = ($input['rrze_search_resources']) ? $input['rrze_search_resources'] : $option['rrze_search_resources'];
        foreach ($output['rrze_search_resources'] as $key => $resource) {
            $output['rrze_search_resources'][$key]['resource_name'] = $this->engines[$resource['resource_class']]['label'];
        }

        // Installed Search Engines - Regular Admin Level
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

    /**
     * Print the admin section
     */
    public function printAdminSection(): void
    {
        echo __('Enable Search Engines', 'rrze-search');
    }

    /**
     * Print the superadmin section
     */
    public function printSuperAdminSection(): void
    {
        echo __('Configure Search Engines', 'rrze-search');
    }

    /**
     * Render the engines table (admin functionality)
     *
     * Template: admin-engine-toggle.php
     *
     * @param array $args
     */
    public function enginesToggle(array $args): void
    {
        $name        = $args['label_for'];
        $optionName  = $args['option_name'];
        $optionValue = get_option($optionName);
        $engines     = [];
        /**
         * TODO: Move the following snippet to where the Super Admin make it's changes
         */
        // Add new Resources from Engine Collection
        foreach ($optionValue['rrze_search_resources'] as $resource) {
            $engine = Helper::isResourceEngine($optionName, $resource['resource_id']);
            if (empty($engine)) {
                $engine = [
                    'resource_id'         => $resource['resource_id'],
                    'resource_disclaimer' => $resource['resource_disclaimer'],
                    'enabled'             => false
                ];
            }
            $engine['resource_name']  = $resource['resource_name'];
            $engine['resource_class'] = $resource['resource_class'];
            $engines[]                = $engine;
        }

        // Engine table
        //$engines = array_values($engines);
        require $this->templatesDir.DIRECTORY_SEPARATOR.'admin-engine-toggle.php';
    }

    /**
     * Render the resources table (superadmin functionality)
     *
     * Template: admin-engine-configuration.php
     *
     * @param array $args Arguments
     */
    public function enginesConfigure(array $args): void
    {
        $name        = $args['label_for'];
        $optionName  = $args['option_name'];
        $optionValue = get_option($optionName);

        // Define props used in template
        $resources = $optionValue[$name];

        // Resource table
        require $this->templatesDir.DIRECTORY_SEPARATOR.'admin-engine-configuration.php';

        // Resource template
        require $this->templatesDir.DIRECTORY_SEPARATOR.'admin-engine-template.php';
    }

    /**
     * Renders disabled input field with search results page name
     *
     * Template: admin-results-page-input.php
     *
     * @param array $args Arguments
     */
    public function resultsPage($args): void
    {
        $name        = $args['label_for'];
        $optionName  = $args['option_name'];
        $optionValue = get_option($optionName);

        if (array_key_exists($name, $optionValue)) {
            // Test the Permalink to ensure current user isn't overwriting post created by another user
            if ($optionValue[$name] === '' || !get_permalink($optionValue[$name])) {
                $rrze_search_page    = [
                    'post_date'     => date('Y-m-d H:i:s'),
                    'post_date_gmt' => date('Y-m-d H:i:s'),
                    'post_content'  => '[rrze_search_results]',
                    'post_name'     => 'rrze_search_page',
                    'post_title'    => __('RRZE Search Results', 'rrze-search'),
                    'post_status'   => 'publish',
                    'post_type'     => 'page',
                    'post_excerpt'  => __('Search Result Page utilized by RRZE Search Plugin', 'rrze-search'),
                ];
                $rrze_search_page_id = wp_insert_post($rrze_search_page);
                $optionValue[$name]  = $rrze_search_page_id;
                update_option($optionName, $optionValue, true);
            }

            if (get_post($optionValue[$name])) {
                require $this->templatesDir.DIRECTORY_SEPARATOR.'admin-results-page-input.php';
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