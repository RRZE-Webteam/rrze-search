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
    protected $engines = ['WpSearch' => 'Default'];

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
        $enginesDirectory = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Engines';
        foreach (scandir($enginesDirectory) as $engineFile) {
            if ($engineFile !== "." && $engineFile !== ".." && $engineFile !== 'SearchEngine-template.php') {
                $engineName = pathinfo($engineFile, PATHINFO_FILENAME);
//            require_once $enginesDirectory.DIRECTORY_SEPARATOR.$engineFile;
                $engineClassName                 = 'RRZE\\RRZESearch\\Ports\\Engines\\'.$engineName;
                $this->engines[$engineClassName] = call_user_func([$engineClassName, 'getName']);
            }
        }

        /**
         * Define Disclaimer Pages
         *
         * Usage: $this->pages
         */
        $this->pages = get_pages();
    }

    public function sanitize($input)
    {
        $output = array();

        /** Installed Search Engines - Super Admin Level (soon) */
        $output['rrze_search_engines'] = $input['rrze_search_engines'] ?? array();
        /** Configured Search Engines - Admin Level */
        $output['rrze_search_resources'] = $input['rrze_search_resources'] ?? array();
        /** Create Array of Enabled Engines */
        $enabledEngines = array();
        foreach ($input['rrze_search_engines'] as $engine) {
            $enabledEngines[$engine['class']] = isset($engine['enabled']) ? 'true' : 'false';
        }
        /** If the engine doesn't have an isEnabled property - it's must likely a newly added element */
        foreach ($input['rrze_search_resources'] as $engine) {
            if (!isset($engine['isEnabled'])) {
                /** Append the property and give it the actual enabled status */
                $output['rrze_search_resources'][count($output['rrze_search_resources']) - 1]['isEnabled'] = $enabledEngines[$engine['resource_class']];
            }
        }
        /** Page ID for Search Results */
        $output['rrze_search_page_id'] = $input['rrze_search_page_id'] ?? '';
        /** Custom Field value for pages tagged as disclaimer pages */
        $output['meta_shortcut'] = 'rrze_search_resource_disclaimer';

        return $output;
    }

    /**
     * TODO: Update Message Text from English to German
     */
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

    public function enginesTable(array $args)
    {
        $name         = $args['label_for'];
        $option_name  = $args['option_name'];
        $option_value = get_option($option_name);

        /**
         * Define props used in template
         */
        $engines = array();

        /**
         * Build Data Provider for table rendering
         */
        $i = 0;
        foreach ($this->engines as $engine_class => $engine_name) {
            if ($i !== 0) {
                $ref                    = new $engine_class;
                $engines[$engine_class] = array(
                    'name' => $ref::NAME,
                    'uri'  => $ref::URI
                );
            }

            $i++;
        }

        /** Engine table */
        require($this->plugin_path.'RRZESearch'.DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'admin-engines-table.php');
    }

    /**
     * Render the resources table
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
        $pages     = array();
        $engines   = array();
        $resources = $option_value[$name];

        /**
         * Filter for Customer Filed value
         */
        foreach ($this->pages as $page) {
            $meta = get_post_meta($page->ID);
            if (isset($meta['rrze_search_resource_disclaimer'])) {
                $pages[] = $page;
            }
        }

        /**
         * Filter for Enabled Engines
         */
        foreach ($option_value['rrze_search_engines'] as $engine) {
            $enable                    = (isset($engine['enabled'])) ? 'true' : 'false';
            $engines[$engine['class']] = array(
                'enabled' => $enable
            );
        }

        /** Resource table */
        require($this->plugin_path.'RRZESearch'.DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'admin-resources-table.php');

        /** Resource template */
        require($this->plugin_path.'RRZESearch'.DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'template-resource.php');
    }

    /**
     * Renders the disclaimer page drop down
     *
     * @param array $args Arguments
     */
    public function disclaimerDropDown($args)
    {
        $name         = $args['label_for'];
        $option_name  = $args['option_name'];
        $option_value = get_option($option_name);
        $options      = $args['options'];

        $output = '<select id="'.$name.'" name="'.$option_name.'['.$name.']'.'">';
        foreach ($options as $option) {
            if ($option['ID'] == $option_value[$name]) {
                $output .= '<option value="'.$option['ID'].'" selected>'.$option['post_title'].'</option>';
            } else {
                $output .= '<option value="'.$option['ID'].'" >'.$option['post_title'].'</option>';
            }
        }
        $output .= '</select>';

        echo $output;
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
                /**
                 * TODO: Update labels from English to German [ post_title, post_excerpt ]
                 */
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
                if (get_post($options[$name]) && intval(get_post($options[$name])->ID) == $options[$name]) {
                    echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']" value="'.get_post($options[$name])->ID.'" >';
                    echo '<input type="text" value="'.wp_make_link_relative(get_permalink($options[$name])).'" class="regular-text" readonly>';
                } else {
                    echo $this->printMissingTemplateMsg();
                }
            }
        } else {
            echo $this->printGenerateTemplateMsg();
        }

    }
}