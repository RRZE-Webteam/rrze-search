<?php

namespace RRZE\RRZESearch\Infrastructure\Settings;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Application\Controller\AppController;
use RRZE\RRZESearch\Infrastructure\Helper\Helper;

/**
 * Renders RRZE Search settings fields inside the WordPress admin.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class OptionFieldRenderer extends AppController
{
    /**
     * Absolute path to the plugin's admin template directory.
     *
     * @var string
     */
    protected $templatesDir;

    /**
     * Prepares the base template directory used by field renderers.
     */
    public function __construct()
    {
        parent::__construct();

        // Shortcut to template directory
        $this->templatesDir = $this->pluginPath.Helper::toDirectory(['Infrastructure', 'Templates']);
    }

    /**
     * Renders the per-site engines toggle table for regular administrators.
     *
     * Uses the `admin-engine-toggle.php` template to list installed engines.
     *
     * @param array<string, mixed> $args Field configuration passed by Settings API.
     *
     * @return void
     */
    public function enginesToggle(array $args): void
    {
        $fieldName   = $args['label_for'];
        $optionName  = $args['option_name'];
        $optionValue = get_option($optionName);

        // Define props used in template
        $engines = $optionValue[$fieldName];

        // Engine table
        require $this->templatesDir.DIRECTORY_SEPARATOR.'admin-engine-toggle.php';
    }

    /**
     * Renders the super administrator resource configuration table and template.
     *
     * Includes both the main configuration view and the row template for new resources.
     *
     * @param array<string, mixed> $args Field configuration passed by Settings API.
     *
     * @return void
     */
    public function enginesConfigure(array $args): void
    {
        $fieldName   = $args['label_for'];
        $optionName  = $args['option_name'];
        $optionValue = get_option($optionName);

        // Define props used in template
        $resources = $optionValue[$fieldName];

        // Resource table
        require $this->templatesDir.DIRECTORY_SEPARATOR.'admin-engine-configuration.php';

        // Resource template
        require $this->templatesDir.DIRECTORY_SEPARATOR.'admin-engine-template.php';
    }

    /**
     * Renders the search results page selector and creates the page if missing.
     *
     * Uses `admin-results-page-input.php` to show the current page and ensures
     * the referenced post exists, recreating it when necessary.
     *
     * @param array<string, mixed> $args Field configuration passed by Settings API.
     *
     * @return void
     */
    public function resultsPage(array $args): void
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
                    'post_title'    => __('Global Search Results', 'rrze-search'),
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
