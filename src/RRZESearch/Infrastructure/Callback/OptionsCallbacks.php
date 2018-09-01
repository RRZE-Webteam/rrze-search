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
    protected $engines = ['WpSearch' => 'Lokale Suche'];

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
            if ($engineFile !== "." && $engineFile !== "..") {
                $engineName = pathinfo($engineFile, PATHINFO_FILENAME);
//            require_once $enginesDirectory.DIRECTORY_SEPARATOR.$engineFile;
                $engineClassName = 'RRZE\\RRZESearch\\Ports\\Engines\\'.$engineName;
//                $this->engines[$engineClassName] = call_user_func([$engineClassName, 'getName']);
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
        $output                           = array();
        $output['meta_shortcut']          = 'rrze_search_resource_disclaimer';
        $output['rrze_search_page_id']    = $input['rrze_search_page_id'] ?? '';
        $output['rrze_search_resources']  = $input['rrze_search_resources'] ?? array();
        $output['rrze_search_disclaimer'] = $input['rrze_search_disclaimer'] ?? '';

        return $output;
    }

    /**
     * TODO: Update Message Text from English to German
     */
    public function printAdminSection()
    {
        echo 'Configure your plugin.';
    }

    public function printMissingTemplateMsg(): string
    {
        return 'Oh no! Someone deleted the result\'s Page! No worries, Another one will be generated when you click [ Save Changes ]';
    }

    public function printGenerateTemplateMsg(): string
    {
        return 'Search Results Page doesn\'t exist, yet! No worries, one will be generated when you click [ Save Changes ]';
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
        $pages = array();
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

        /** Resource template */
        require($this->plugin_path.'RRZESearch'.DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'template-resource.php');

        /** Resource table */
        require($this->plugin_path.'RRZESearch'.DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'admin-resources-table.php');
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
                    'post_title'    => 'RRZE Search Results',
                    'post_status'   => 'publish',
                    'post_type'     => 'page',
                    'post_excerpt'  => 'Search Results Page utilized by RRZE Search Plugin',
                );
                $rrze_search_page_id = wp_insert_post($rrze_search_page);
                $options[$name]      = $rrze_search_page_id;
                update_option($option_name, $options, true);
            } else {
                if (get_post($options[$name]) && intval(get_post($options[$name])->ID) == $options[$name]) {
                    echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']" value="'.get_post($options[$name])->ID.'" >';
                    echo '<input type="text" value="'.wp_make_link_relative(get_permalink($options[$name])).'" disabled>';
                } else {
                    echo $this->printMissingTemplateMsg();
                }
            }
        } else {
            echo $this->printGenerateTemplateMsg();
        }

    }
}