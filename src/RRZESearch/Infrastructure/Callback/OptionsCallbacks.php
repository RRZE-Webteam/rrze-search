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
    protected $engines = ['' => 'Lokale Suche'];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $enginesDirectory = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Engines';
        foreach (scandir($enginesDirectory) as $engineFile) {
            if ($engineFile !== "." && $engineFile !== "..") {
                $engineName = pathinfo($engineFile, PATHINFO_FILENAME);
//            require_once $enginesDirectory.DIRECTORY_SEPARATOR.$engineFile;
                $engineClassName                 = 'RRZE\\RRZESearch\\Ports\\Engines\\'.$engineName;
                $this->engines[$engineClassName] = call_user_func([$engineClassName, 'getName']);
            }
        }
    }

    public function sanitize($input)
    {
        $output                           = array();
        $output['rrze_search_resources']  = (isset($input['rrze_search_resources'])) ? $input['rrze_search_resources'] : array();
        $output['rrze_search_disclaimer'] = (isset($input['rrze_search_disclaimer'])) ? $input['rrze_search_disclaimer'] : '';
        $output['rrze_search_page_id']    = (isset($input['rrze_search_page_id'])) ? $input['rrze_search_page_id'] : '';;

        return $output;
    }

    public function printAdminSection()
    {
        echo "Configure your plugin.";
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
        $resources    = $option_value[$name];

        $output = '<table id="rrze_search_resource_form" class="form-table" border="0">';
        $output .= '<thead>';
        $output .= '<td><strong>Search Engine</strong></td>';
        $output .= '<td><strong>URL</strong></td>';
        $output .= '<td><strong>API Key</strong></td>';
        $output .= '<td>&nbsp;</td>';
        $output .= '</thead>';
        $i      = 0;

        foreach ($resources as $resource) {
            $output .= '<tr valign="top">';
            $output .= '<td><input type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_name]" value="'.$resource['resource_name'].'" /></td>';

            if ($i == 0) {
                $output .= '<td>WordPress Search';
                $output .= '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_uri]" value=""/>';
                $output .= '</td>';
            } else {
                $output .= '<td><input type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_uri]" value="'.$resource['resource_uri'].'" /></td>';
            }

            if ($i == 0) {
                $output .= '<td>No API Key Required';
                $output .= '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_key]" value="" />';
                $output .= '</td>';
            } else {
                $output .= '<td><input type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_key]" value="'.$resource['resource_key'].'" /></td>';
            }

            if ($i == 0) {
                $output .= '<td><input type="button" class="button button-primary" value="Remove" disabled></td>';
            } else {
                $output .= '<td><a href="javascript:rrze_resource_removal('.$i.')" class="button button-primary">Remove</a></td>';
            }

            $output .= '</tr>';
            $i++;
        }

        $output .= '<tfoot>';
        $output .= '<td colspan="4" align="right">';
        $output .= '<input type="hidden" id="rrze_search_resource_count" value="'.$i.'">';
        $output .= '<input type="button" id="rrze_search_add_resource_form" class="button button-primary" value="Add Resource">';
        $output .= '</td>';
        $output .= '</tfoot>';
        $output .= '</table>';
        echo $output;
    }

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
                    'post_title'    => 'Search Results',
                    'post_status'   => 'publish',
                    'post_type'     => 'page',
                    'post_excerpt'  => 'Search Results',
                );
                $rrze_search_page_id = wp_insert_post($rrze_search_page);
                $options[$name]      = $rrze_search_page_id;
                update_option($option_name, $options, true);
            } else {
                if (get_post($options[$name]) && intval(get_post($options[$name])->ID) == $options[$name]) {
                    echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']" value="'.get_post($options[$name])->ID.'" >';
                    echo '<input type="text" value="'.wp_make_link_relative(get_permalink($options[$name])).'" disabled>';
                } else {
                    echo 'someone deleted the page';
                }
            }
        } else {
            echo 'Search Results Template doesn\'t exist. A template will be generated when you Save Changes.';
        }

    }
}