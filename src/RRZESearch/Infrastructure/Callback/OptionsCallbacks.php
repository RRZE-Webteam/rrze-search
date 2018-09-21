<?php

namespace RRZE\RRZESearch\Infrastructure\Callback;

use RRZE\RRZESearch\Application\AppController;

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

    public function sanitize($input)
    {
        $output = array();

        /** Configured Search Engines - Super Admin Level */
        $output['rrze_search_resources'] = $input['rrze_search_resources'] ?? array();

        /** Installed Search Engines - Regular Admin Level */
        $output['rrze_search_engines'] = $input['rrze_search_engines'] ?? array();
//        $output['rrze_search_engines'] = [];

        /** Page ID for Search Results */
        $output['rrze_search_page_id'] = $input['rrze_search_page_id'] ?? '';
        /** Custom Field value for pages tagged as disclaimer pages */
        $output['meta_shortcut'] = 'rrze_search_resource_disclaimer';

        return $output;
    }

    public function printAdminSection()
    {
        echo __('Configure your plugin.', 'rrze-search');
    }

    public function printMissingTemplateMsg(): string
    {
        return __('Oh no! Someone deleted the results Page! No worries, Another one will be generated when you click [ Save Changes ]',
            'rrze-search');
    }

    public function printGenerateTemplateMsg(): string
    {
        return __('Search Results Page doesn\'t exist, yet! No worries, one will be generated when you click [ Save Changes ]',
            'rrze-search');
    }

    /**
     * Render the engines table - Admin
     *
     * @param array $args
     */
    public function enginesTable(array $args)
    {
        $name         = $args['label_for'];
        $option_name  = $args['option_name'];
        $option_value = get_option($option_name);

        $enginesCount = \count($option_value[$name]);

//        if (\count($option_value['rrze_search_resources']) !== $enginesCount) {
        /** Add or Remove Additional Resources from Engine Collection */

        $_array = [];
        foreach ($option_value['rrze_search_resources'] as $resource) {
            if ($this->isResourceEngine($option_name, $resource['resource_id'])) {
                $_array[] = [
                    'validator'   => $this->isResourceEngine($option_name, $resource['resource_id']),
                    'resource_id' => $resource['resource_id'],
                    'enabled'     => $this->isEngineEnabled($option_name, $resource['resource_id'])
                ];
//                $option_value[$name][] = [
//                    'resource_id'    => $resource['resource_id'],
//                    'resource_name'  => $resource['resource_name'],
//                    'resource_class' => $resource['resource_class'],
//                    'enabled'        => $this->isEngineEnabled($option_name, $resource['resource_id'])
//                ];
            } else {
                echo 'do something else for: '.$resource['resource_id'];
            }
        }

        echo '<pre>';
        print_r($_array);
        echo '</pre>';

//        }

        /** Engine table */
        require $this->facades_dir.DIRECTORY_SEPARATOR.'admin-engines-table.php';
    }

    private function isResourceEngine($option_name, $resource_id): bool
    {
        $bool         = false;
        $option_value = get_option($option_name);
        foreach ($option_value['rrze_search_resources'] as $resource) {
            if ($resource['resource_id'] === $resource_id) {
                $bool = true;
            }
        }

        return $bool;
    }

    private function isEngineEnabled($option_name, $resource_id): bool
    {
        $bool         = false;
//        $option_value = get_option($option_name);
//        foreach ($option_value['rrze_search_engines'] as $engine) {
//            if ($engine['resource_id'] === $resource_id) {
//                $bool = $engine['enabled'];
//            }
//        }

        return $bool;
    }

    /**
     * Render the resources table - Super Admin
     *
     * @param array $args Arguments
     */
    public function resourcesTable(array $args)
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
     * @param array $args Arguments
     */
    public function disabledInput($args)
    {
        $name        = $args['label_for'];
        $option_name = $args['option_name'];
        $options     = get_option($option_name);

        if (array_key_exists($name, $options)) {
            if ($options[$name] == null) {
                $rrze_search_page    = array(
                    'post_date'     => date('Y-m-d H:i:s'),
                    'post_date_gmt' => date('Y-m-d H:i:s'),
                    'post_content'  => '[rrze_search_results]',
                    'post_name'     => 'rrze_search_page',
                    'post_title'    => __('RRZE Search Results', 'rrze-search'),
                    'post_status'   => 'publish',
                    'post_type'     => 'page',
                    'post_excerpt'  => __('Search Result Page utilized by RRZE Search Plugin', 'rrze-search'),
                );
                $rrze_search_page_id = wp_insert_post($rrze_search_page);
                $options[$name]      = $rrze_search_page_id;
                update_option($option_name, $options, true);
            } else {
                if (get_post($options[$name]) && (int)get_post($options[$name])->ID == $options[$name]) {
                    require $this->facades_dir.DIRECTORY_SEPARATOR.'admin-results-page-input.php';
                } else {
                    echo $this->printMissingTemplateMsg();
                }
            }
        } else {
            echo $this->printGenerateTemplateMsg();
        }

    }
}