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
class OptionsFields extends AppController
{
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

        // Shortcut to template directory
        $this->templatesDir = $this->pluginPath.Helper::toDirectory(['RRZESearch', 'Infrastructure', 'Templates']);
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
        $fieldName   = $args['label_for'];
        $optionName  = $args['option_name'];
        $optionValue = get_option($optionName);

//        echo '<pre>';
//        echo $fieldName.': '.PHP_EOL;
//        print_r($optionValue[$fieldName]);
//        print_r($this->enginesClassCollection);
//        echo PHP_EOL;
//        echo '</pre>';

        // Define props used in template
        $engines     = $optionValue[$fieldName];

        // Engine table
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
        $fieldName   = $args['label_for'];
        $optionName  = $args['option_name'];
        $optionValue = get_option($optionName);

        echo '<table border="1">';
        echo '<td style="font-size:0.9em;">';
            echo 'rrze_search_engines: '.PHP_EOL;
            echo '<pre>';
            print_r($optionValue['rrze_search_engines']);
            echo '</pre>';
        echo '</td><td style="font-size:0.9em;">';
            echo $fieldName.': '.PHP_EOL;
            echo '<pre>';
            print_r($optionValue[$fieldName]);
            echo '</pre>';
        echo '</td>';
        echo '</table>';

        // Define props used in template
        $resources = $optionValue[$fieldName];

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