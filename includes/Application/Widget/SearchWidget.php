<?php

namespace RRZE\RRZESearch\Application\Widget;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Domain\Contract\Engine;
use RRZE\RRZESearch\Infrastructure\Helper\Helper;
use WP_Widget;

/**
 * Renders the RRZE multisearch widget and manages its lifecycle.
 *
 * Registers the widget, injects it into a dedicated sidebar, and orchestrates
 * engine selection and redirects based on user preferences.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Application
 */
class SearchWidget extends WP_Widget
{
    /**
     * Unique identifier used when registering the widget.
     *
     * @var string
     */
    protected $widgetId;
    /**
     * Human-readable widget name displayed in the admin area.
     *
     * @var string
     */
    protected $widgetName;
    /**
     * Widget options passed to the parent WP_Widget constructor.
     *
     * @var array<string, mixed>
     */
    public $widgetOptions = [];
    /**
     * RRZE Search plugin settings loaded from the options table.
     *
     * @var array<string, mixed>
     */
    protected $options;
    /**
     * Base plugin path used when including templates.
     *
     * @var string
     */
    public $pluginPath;
    /**
     * Registered search engines keyed by their adapter class names.
     *
     * @var array<string, array<string, mixed>>
     */
    public $enginesClassCollection = [];

    /**
     * Prepares default widget configuration and boots available engines.
     */
    public function __construct()
    {
        $this->widgetId = 'rrze_search';
        $this->widgetName = 'Suche (Multi-Engine)';
        $this->widgetOptions = [
            'classname' => $this->widgetId,
            'description' => $this->widgetName,
            'customize_selective_refresh' => true,
        ];

        $this->options = get_option('rrze_search_settings');
        $this->pluginPath = plugin_dir_url(dirname(__FILE__, 2));
        $this->enginesClassCollection = Helper::adapterCollection();

        register_activation_hook(__FILE__, [$this, 'widgetSubmit']);
        parent::__construct($this->widgetId, $this->widgetName);
    }

    /**
     * Registers the widget hooks responsible for rendering and form handling.
     *
     * @return void
     */
    public function register(): void
    {
        parent::__construct($this->widgetId, $this->widgetName, $this->widgetOptions);
        add_action('widgets_init', [$this, 'widgetsInit']);
        add_action('admin_post_nopriv_widget_form_submit', [$this, 'widgetSubmit']);
        add_action('admin_post_widget_form_submit', [$this, 'widgetSubmit']);
    }

    /**
     * Registers the widget and ensures the dedicated sidebar exists.
     *
     * @return void
     */
    public function widgetsInit(): void
    {
        // Register Widget with WordPress
        register_widget($this);

        // Register Sidebar with WordPress
        $sidebarId = 'rrze-search-sidebar';
        register_sidebar([
            'name' => __('RRZE Search Sidebar', 'rrze-search'),
            'id' => $sidebarId,
            'description' => __('Used to display the widget', 'rrze-search'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '',
            'after_title' => '',
        ]);

        if (!is_active_widget(false, false, $this->widgetId, true)) {
            $this->insertWidgetInSidebar($this->widgetId, [
                'title' => '',
                'search_engine' => 0
            ], $sidebarId);
        }
    }

    /**
     * Injects a widget instance into the given sidebar if none exists.
     *
     * Adapted from https://gist.github.com/tyxla/372f51ea1340e5e643f6b47e2ddf43f2.
     *
     * @param string $widgetId Widget base ID.
     * @param array<string, mixed> $widgetData Widget instance data.
     * @param string $sidebar Sidebar identifier.
     *
     * @return void
     */
    public function insertWidgetInSidebar(string $widgetId, array $widgetData, string $sidebar): void
    {
        // Retrieve sidebars, widgets and their instances
        $sidebarWidgets = get_option('sidebars_widgets', []);
        $widgetInstances = get_option('widget_' . $widgetId, []);

        // Retrieve the key of the next widget instance
        $numericKeys = array_filter(array_keys($widgetInstances), 'is_int');
        $nextKey = $numericKeys ? max($numericKeys) + 1 : 2;

        // Add this widget to the sidebar
        if (!isset($sidebarWidgets[$sidebar])) {
            $sidebarWidgets[$sidebar] = [];
        }
        $sidebarWidgets[$sidebar][] = $widgetId . '-' . $nextKey;

        // Add the new widget instance
        $widgetInstances[$nextKey] = $widgetData;

        // Store updated sidebars, widgets and their instances
        update_option('sidebars_widgets', $sidebarWidgets);
        update_option('widget_' . $widgetId, $widgetInstances);
    }

    /**
     * Sanitizes widget settings when saved in the WordPress admin.
     *
     * @param array<string, mixed> $newInstance Proposed widget settings.
     * @param array<string, mixed> $oldInstance Persisted widget settings.
     *
     * @return array<string, mixed> Cleaned widget settings.
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
     * Outputs the widget form allowing admins to configure defaults.
     *
     * @param array<string, mixed> $instance Current widget settings.
     *
     * @return void
     */
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $searchEngine = !empty($instance['search_engine']) ? $instance['search_engine'] : '0';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('search_engine'); ?>"><?php echo __('Default Search Engine',
                    'rrze-search'); ?>:</label>
            <select name="<?php echo $this->get_field_name('search_engine'); ?>"
                    id="<?php echo $this->get_field_id('search_engine'); ?>" class="widefat">
                <?php foreach ($this->options['rrze_search_resources'] as $key => $resource) {
                    echo '<option value="' . $key . '" ' . selected($searchEngine, $key,
                            false) . '>' . $resource['resource_name'] . '</option>';
                } ?>
            </select>
        </p>
        <?php
    }

    /**
     * Renders the search widget on the frontend.
     *
     * @param array<string, mixed> $args Widget display arguments.
     * @param array<string, mixed> $instance Widget settings for the current instance.
     *
     * @return void
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        $preferredEngine = empty($_COOKIE['rrze_search_engine_pref']) ? (int)$instance['search_engine'] : (int)$_COOKIE['rrze_search_engine_pref'];
        $resources = [];

        foreach ($this->options['rrze_search_engines'] as $key => $engine) {
            /** @var Engine $class */
            $class = new $engine['resource_class'];
            $resource = Helper::getResourceById('rrze_search_settings', $engine['resource_id']);
            $resources[$key] = $engine;
            $resources[$key]['link_label'] = $class::getLinkLabel();
            $resources[$key]['args'] = array();
            if (isset($resource['args'])) {
                $resources[$key]['args'] = $resource['args'];
            }
        }
        include \dirname(__DIR__,
                2) . DIRECTORY_SEPARATOR . 'Infrastructure' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'widget.php';

        echo $args['after_widget'];
    }

    /**
     * Handles widget submissions and redirects to the selected engine results.
     *
     * @return void
     */
    public function widgetSubmit(): void
    {
        $resourceId = isset($_POST['resource_id']) ? absint($_POST['resource_id']) : null;

        $engines = $this->options['rrze_search_engines'] ?? [];
        $engineEntry = null;
        $engineKey = null;

        if ($resourceId !== null && isset($engines[$resourceId])) {
            $engineEntry = $engines[$resourceId];
            $engineKey = $resourceId;
        }

        if (!$engineEntry) {
            foreach ($engines as $key => $candidate) {
                if (!empty($candidate['resource_class']) && class_exists($candidate['resource_class'])) {
                    $engineEntry = $candidate;
                    $engineKey = $key;
                    break;
                }
            }
        }

        if (!$engineEntry || empty($engineEntry['resource_class']) || !class_exists($engineEntry['resource_class'])) {
            wp_safe_redirect(add_query_arg('rrze_search_error', 'invalid_engine', wp_get_referer() ?: home_url('/')));
            exit;
        }

        setcookie('rrze_search_engine_pref', (int)$engineKey, 0, '/');

        $engineClass = $engineEntry['resource_class'];
        $class = new $engineClass();
        $results_page = $class->getRedirectLink();

        $searchTerm = isset($_POST['s']) ? sanitize_text_field(wp_unslash($_POST['s'])) : '';

        $_q = ($class->getRedirectLink() !== '/') ? 'q' : 's';

        // Ensure you're using $_POST['s'] for the q(uery) value, prior to redirect
        $redirect_link = add_query_arg(
            [$_q => $searchTerm, 'se' => (int)$engineKey],
            $results_page
        );

        wp_redirect($redirect_link);
        exit;
    }
}
