<?php

namespace RRZE\RRZESearch\Application\Widget;

use RRZE\RRZESearch\Domain\Contract\Engine;
use WP_Widget;

/**
 * SearchWidget
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Application
 */
class SearchWidget extends WP_Widget
{
    /**
     * Widget ID
     *
     * @var string
     */
    protected $widgetId;
    /**
     * Widget name
     *
     * @var string
     */
    protected $widgetName;
    /**
     * Widget options
     *
     * @var array
     */
    public $widgetOptions = [];
    /**
     * Plugin options
     *
     * @var array
     */
    protected $options;
    /**
     * Plugin path
     *
     * @var string
     */
    public $pluginPath;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->widgetId      = 'rrze_search';
        $this->widgetName    = 'Suche (Multi-Engine)';
        $this->widgetOptions = [
            'classname'                   => $this->widgetId,
            'description'                 => $this->widgetName,
            'customize_selective_refresh' => true,
        ];

        $this->options    = get_option('rrze_search_settings');
        $this->pluginPath = plugin_dir_url(dirname(__FILE__, 2));
        register_activation_hook(__FILE__, [$this, 'widgetSubmit']);
        parent::__construct($this->widgetId, $this->widgetName);
    }

    /**
     * Register the plugin
     */
    public function register()
    {
        parent::__construct($this->widgetId, $this->widgetName, $this->widgetOptions);
        add_action('widgets_init', [$this, 'widgetsInit']);
        add_action('admin_post_nopriv_widget_form_submit', [$this, 'widgetSubmit']);
        add_action('admin_post_widget_form_submit', [$this, 'widgetSubmit']);
    }

    /**
     * Widget initializatopn
     */
    public function widgetsInit()
    {
        // Register Widget with WordPress
        register_widget($this);

        // Register Sidebar with WordPress
        $sidebarId = 'rrze-search-sidebar';
        register_sidebar([
            'name'          => __('RRZE Search Sidebar', 'rrze-search'),
            'id'            => $sidebarId,
            'description'   => __('Used to display the widget', 'rrze-search'),
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        ]);

        if (!is_active_widget(false, false, $this->widgetId, true)) {
            $this->insertWidgetInSidebar($this->widgetId, [
                'title'         => '',
                'search_engine' => 0
            ], $sidebarId);
        }
    }

    /**
     * Insert Widget in Sidebar
     * Source: https://gist.github.com/tyxla/372f51ea1340e5e643f6b47e2ddf43f2
     *
     * @param string $widgetId  Widget ID
     * @param array $widgetData Widget data
     * @param string $sidebar
     */
    public function insertWidgetInSidebar($widgetId, $widgetData, $sidebar): void
    {
        // Retrieve sidebars, widgets and their instances
        $sidebarWidgets  = get_option('sidebars_widgets', []);
        $widgetInstances = get_option('widget_'.$widgetId, []);

        // Retrieve the key of the next widget instance
        $numericKeys = array_filter(array_keys($widgetInstances), 'is_int');
        $nextKey     = $numericKeys ? max($numericKeys) + 1 : 2;

        // Add this widget to the sidebar
        if (!isset($sidebarWidgets[$sidebar])) {
            $sidebarWidgets[$sidebar] = [];
        }
        $sidebarWidgets[$sidebar][] = $widgetId.'-'.$nextKey;

        // Add the new widget instance
        $widgetInstances[$nextKey] = $widgetData;

        // Store updated sidebars, widgets and their instances
        update_option('sidebars_widgets', $sidebarWidgets);
        update_option('widget_'.$widgetId, $widgetInstances);
    }

    /**
     * Process Widget Update
     * WordPress Admin Function
     *
     * @param array $newInstance New widget instance
     * @param array $oldInstance Old widget instance
     *
     * @return array  Updated widget instance
     */
    public function update($newInstance, $oldInstance)
    {
        $instance = $oldInstance;
        foreach (array_keys($newInstance) as $key) {
            $instance[$key] = sanitize_text_field($newInstance[$key]);
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
        $title        = !empty($instance['title']) ? $instance['title'] : '';
        $searchEngine = !empty($instance['search_engine']) ? $instance['search_engine'] : '0';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('search_engine'); ?>"><?php echo __('Default Search Engine',
                    'rrze-search'); ?>:</label>
            <select name="<?php echo $this->get_field_name('search_engine'); ?>"
                    id="<?php echo $this->get_field_id('search_engine'); ?>" class="widefat">
                <?php foreach ($this->options['rrze_search_resources'] as $key => $resource) {
                    echo '<option value="'.$key.'" '.selected($searchEngine, $key,
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
        $preferredEngine = empty($_COOKIE['rrze_search_engine_pref']) ? (int)$instance['search_engine'] : (int)$_COOKIE['rrze_search_engine_pref'];

        $resources = [];
        foreach ($this->options['rrze_search_engines'] as $key => $engine) {
            /** @var Engine $class */
            $class                         = new $engine['resource_class'];
            $resources[$key]               = $engine;
            $resources[$key]['link_label'] = $class::getLinkLabel();
        }

        $staticLinks = trim(file_get_contents(\dirname(__DIR__,
                2).DIRECTORY_SEPARATOR.'Infrastructure'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'widget-static-links.json'));
        $staticLinks = strlen($staticLinks) ? json_decode($staticLinks) : [];
        $staticLinks = is_array($staticLinks) ? $staticLinks : [];

        include \dirname(__DIR__,
                2).DIRECTORY_SEPARATOR.'Infrastructure'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'widget.php';
        echo $args['after_widget'];
    }

    /**
     * Additional Functions
     */
    public function widgetSubmit()
    {
        setcookie('rrze_search_engine_pref', $_POST['resource_id'], 0, '/');
        $results_page = get_permalink($this->options['rrze_search_page_id']);

        // Ensure you're using $_POST['s'] for the q(uery) value, prior to redirect
        $redirect_link = add_query_arg(
            ['q' => urlencode($_POST['s']), 'se' => $_POST['resource_id']],
            $results_page
        );
        wp_redirect($redirect_link);
        exit;
    }
}