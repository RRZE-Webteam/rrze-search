<?php

namespace RRZE\RRZESearch\Application\Widgets;

use WP_Widget;

class SearchWidget extends WP_Widget
{
    public $widget_ID;
    public $widget_name;
    public $widget_options = array();

    public $options;

    public $plugin_path;

    public function __construct()
    {
        $this->widget_ID      = 'rrze_search_widget';
        $this->widget_name    = 'Suche (Multi-Engine)';
        $this->widget_options = array(
            'classname'                   => $this->widget_ID,
            'description'                 => $this->widget_name,
            'customize_selective_refresh' => true,
        );

        $this->options     = get_option('rrze_search_settings');
        $this->plugin_path = plugin_dir_url(dirname(__FILE__, 2));

        register_activation_hook(__FILE__, array($this, 'widgetSubmit'));

    }

    public function register()
    {
        parent::__construct($this->widget_ID, $this->widget_name, $this->widget_options);
        add_action('widgets_init', array($this, 'widgetsInit'));
        add_action('admin_post_nopriv_widget_form_submit', array($this, 'widgetSubmit'));
        add_action('admin_post_widget_form_submit', array($this, 'widgetSubmit'));
    }

    public function widgetsInit()
    {
        register_widget($this);
        register_sidebar(array(
            'name'          => 'Search Sidebar',
            'id'            => 'rrze-search-sidebar',
            'description'   => 'A hack to display widget',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        ));
    }

    /**
     * Process Widget Update
     * WordPress Admin Function
     *
     * @param array $new_instance
     * @param array $old_instance
     *
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        foreach (array_keys($new_instance) as $key) {
            $instance[$key] = sanitize_text_field($new_instance[$key]);
        }

        return $instance;
    }

    /**
     * Widget Droplet from WpAdmin/Appearance/Widgets
     *
     * @param array $instance
     *
     * @return string|void
     */
    public function form($instance)
    {
        $title         = !empty($instance['title']) ? $instance['title'] : '';
        $search_engine = !empty($instance['search_engine']) ? $instance['search_engine'] : '0';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Display Title:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('search_engine'); ?>">Default Search Engine:</label>
            <select name="<?php echo $this->get_field_name('search_engine'); ?>"
                    id="<?php echo $this->get_field_id('search_engine'); ?>" class="widefat">
                <?php foreach ($this->options['rrze_search_resources'] as $key => $resource) {
                    echo '<option value="'.$key.'" '.selected($search_engine, $key,
                            false).'>'.$resource['resource_name'].'</option>';
                } ?>
            </select>
        </p>
        <?php
    }

    /**
     * Render the search widget
     *
     * @param array $args     Widget parameters
     * @param array $instance Instance parameters
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        $preferredEngine = empty($_COOKIE['rrze_search_engine_pref']) ? intval($instance['search_engine']) : intval($_COOKIE['rrze_search_engine_pref']);
        include \dirname(__DIR__,
                2).DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'widget.php';

        echo $args['after_widget'];
    }

    /**
     * Additional Functions
     */
    public function widgetSubmit()
    {
        setcookie('rrze_search_engine_pref', $_POST['resource_id'], 0, '/');
        $results_page  = get_permalink($this->options['rrze_search_page_id']);
        $redirect_link = add_query_arg(array('q' => urlencode($_POST['query']), 'se' => $_POST['resource_id']),
            $results_page);

        if ($_POST['resource_id'] != 0) {
            wp_redirect($redirect_link);
        } else {
            wp_redirect(esc_url(home_url('?s='.rawurlencode($_POST['query']))));
        }
        exit;

    }
}