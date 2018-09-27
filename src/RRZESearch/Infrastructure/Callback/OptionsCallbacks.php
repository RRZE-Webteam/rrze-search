<?php

namespace RRZE\RRZESearch\Infrastructure\Callback;

use RRZE\RRZESearch\Application\AppController;
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
     * @var array|false
     */
    private $pages;

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
        $enginesDirectory = \dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Engines';
        foreach (scandir($enginesDirectory, SCANDIR_SORT_NONE) as $engineFile) {
            if ($engineFile !== '.' && $engineFile !== '..' && $engineFile !== 'SearchEngine-template.php') {
                $engineName                      = pathinfo($engineFile, PATHINFO_FILENAME);
                $engineClassName                 = 'RRZE\\RRZESearch\\Ports\\Engines\\'.$engineName;
                $this->engines[$engineClassName] = \call_user_func([$engineClassName, 'getName']);
            }
        }

        /**
         * Define Disclaimer Pages
         */
        $this->pages = get_pages();

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

        /** Installed Search Engines - Regular Admin Level */
        $output['rrze_search_engines'] = ($input['rrze_search_engines']) ? $input['rrze_search_engines'] : $option['rrze_search_engines'];

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
     *
     * @param array $args
     */
    public function enginesTable(array $args): void
    {
        $name         = $args['label_for'];
        $option_name  = $args['option_name'];
        $option_value = get_option($option_name);


        /** Add new Resources from Engine Collection */
        foreach ($option_value['rrze_search_resources'] as $resource) {
            if (!Helper::isResourceEngine($option_name, $resource['resource_id'])) {
                $option_value[$name][] = [
                    'resource_id'    => $resource['resource_id'],
                    'resource_name'  => $resource['resource_name'],
                    'resource_class' => $resource['resource_class'],
                    'enabled'        => false
                ];
            }
        }

        /** Remove old Engines missing from Resources Collection */
        $nextResourceIndex = 0;
        foreach ($option_value['rrze_search_engines'] as $engine) {
            if (!Helper::isEngineResource($option_name, $engine['resource_id'])) {
                array_splice($option_value['rrze_search_engines'], $nextResourceIndex - 1, 1);
            }
            $nextResourceIndex++;
        }

        /** Engine table */
        require $this->facades_dir.DIRECTORY_SEPARATOR.'admin-engines-table.php';
    }

    /**
     * Render the resources table - Super Admin
     *
     * @param array $args Arguments
     */
    public function resourcesTable(array $args): void
    {
        $name         = $args['label_for'];
        $option_name  = $args['option_name'];
        $option_value = get_option($option_name);

        /**
         * Define props used in template
         */
        $disclaimerPages = array();
        $resources       = $option_value[$name];

        /**
         * Filter for Customer Filed value
         */
        foreach ($this->pages as $page) {
            $meta = get_post_meta($page->ID);
            if (isset($meta['rrze_search_resource_disclaimer'])) {
                $disclaimerPages[] = $page;
            }
        }

        /** Resource table */
        require $this->facades_dir.DIRECTORY_SEPARATOR.'admin-resources-table.php';

        /** Resource template */
        require $this->facades_dir.DIRECTORY_SEPARATOR.'template-resource.php';

    }

    /**
     * Renders disabled Input field with Search Results page name
     *
     * TODO: Different users are generating their own page
     *
     * @param array $args Arguments
     */
    public function disabledInput($args): void
    {
        $name          = $args['label_for'];
        $option_name   = $args['option_name'];
        $options_value = get_option($option_name);

        if (array_key_exists($name, $options_value)) {
            if ($options_value[$name] === '') {
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