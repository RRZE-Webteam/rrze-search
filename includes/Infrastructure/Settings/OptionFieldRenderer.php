<?php

namespace RRZE\RRZESearch\Infrastructure\Settings;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Application\Controller\AppController;
use RRZE\RRZESearch\Infrastructure\Helper\Helper;
use RRZE\RRZESearch\Infrastructure\ResultsPage;

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

        $engines = isset($optionValue[$fieldName]) && is_array($optionValue[$fieldName])
            ? $optionValue[$fieldName]
            : [];

        $globalEngines = defined('RRZE_SEARCH_ENGINES') && is_array(RRZE_SEARCH_ENGINES) ? RRZE_SEARCH_ENGINES : [];
        $hasGlobalPresets = !empty($globalEngines);

        $defaultEngine = isset($optionValue['rrze_search_default_engine'])
            ? (string) $optionValue['rrze_search_default_engine']
            : '';

        require $this->templatesDir . DIRECTORY_SEPARATOR . 'admin-engine-toggle.php';
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
        $globalEngines = defined('RRZE_SEARCH_ENGINES') && is_array(RRZE_SEARCH_ENGINES) ? RRZE_SEARCH_ENGINES : [];

        if (!empty($globalEngines)) {
            require $this->templatesDir . DIRECTORY_SEPARATOR . 'admin-engine-presets.php';
        }

        require $this->templatesDir . DIRECTORY_SEPARATOR . 'admin-engine-configuration.php';
        require $this->templatesDir . DIRECTORY_SEPARATOR . 'admin-engine-template.php';
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
        $pageId      = ResultsPage::ensureExists();

        if (is_wp_error($pageId)) {
            echo '<span class="notice notice-error inline"><p>' . esc_html($pageId->get_error_message()) . '</p></span>';

            return;
        }

        $resultsPage = get_post($pageId);

        if (!$resultsPage instanceof \WP_Post) {
            echo '<span class="notice notice-error inline"><p>'
                . esc_html__('The RRZE Search results page could not be loaded.', 'rrze-search')
                . '</p></span>';

            return;
        }

        require $this->templatesDir.DIRECTORY_SEPARATOR.'admin-results-page-input.php';
    }
}
