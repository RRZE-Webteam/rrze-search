<?php

namespace RRZE\RRZESearch\Infrastructure;

use RRZE\RRZESearch\Application\Controller\AppController;
use RRZE\RRZESearch\Infrastructure\Persistence\OptionsCallbacks;
use RRZE\RRZESearch\Infrastructure\Persistence\OptionsPanels;
use RRZE\RRZESearch\Infrastructure\Persistence\SettingsApi;

/**
 * Dashboard
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class Dashboard extends AppController
{
    /**
     * Settings API
     *
     * @var SettingsApi
     */
    protected $settings;
    /**
     * Callbacks
     *
     * @var OptionsPanels
     */
    protected $callbacks;
    /**
     * Callbacks options
     *
     * @var OptionsCallbacks
     */
    protected $callbacksOptions;
    /**
     * Pages
     *
     * @var array
     */
    protected $pages = [];
    /**
     * Subpages
     *
     * @var array
     */
    protected $subpages = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->settings         = new SettingsApi();
        $this->callbacks        = new OptionsPanels();
        $this->callbacksOptions = new OptionsCallbacks();
    }

    /**
     * Registration
     */
    public function register(): void
    {
        $this->pagesConfiguration();
        $this->setSettings();
        $this->setSections();
        $this->setFields();
        $this->settings->addPages($this->pages)->withSubPage(__('Search Engines', 'rrze-search'))->register();
        $this->settings->addSubPages($this->subpages)->register();
    }

    /**
     * Configure the admin page
     */
    public function pagesConfiguration(): void
    {
        $slug = 'rrze_search';

        // Option Admin Page aka Dashboard
        $this->pages = [
            [
                'page_title' => __('Settings › Search', 'rrze-search'),
                'menu_title' => 'RRZE Suche',
                'capability' => 'manage_options',
                'menu_slug'  => $slug,
                'callback'   => [$this->callbacks, 'adminDashboard'],
                'icon_url'   => 'dashicons-search'
            ]
        ];

        // Sub Page is used for the Super Admin Functionality
        $this->subpages = [
            [
                'parent_slug' => 'rrze_search',
                'page_title'  => __('Settings › Search', 'rrze-search'),
                'menu_title'  => __('Configuration', 'rrze-search'),
                'capability'  => 'manage_options',
                'menu_slug'   => $slug.'_su',
                'callback'    => [$this->callbacks, 'superAdminDashboard']
            ]
        ];
    }

    /**
     * Set the settings
     */
    public function setSettings(): void
    {
        // In relation to register_setting() from the WP Settings API
        $args = [
            [
                'option_group' => 'rrze_search_settings',
                'option_name'  => 'rrze_search_settings',
                'callback'     => [$this->callbacksOptions, 'sanitize']
            ]
        ];

        $this->settings->setSettings($args);
    }

    /**
     * Configure the settings
     */
    public function setSections(): void
    {
        $args = [
            [
                'id'       => 'rrze_search_admin_section',
                'title'    => __('Settings › Search', 'rrze-search'),
                'callback' => [$this->callbacksOptions, 'printAdminSection'],
                'page'     => 'rrze_search'
            ],
            [
                'id'       => 'rrze_search_super_admin_section',
                'title'    => __('Settings › Search', 'rrze-search'),
                'callback' => [$this->callbacksOptions, 'printSuperAdminSection'],
                'page'     => 'rrze_search_su'
            ]
        ];
        $this->settings->setSections($args);
    }

    /**
     * Configure the fields
     */
    public function setFields(): void
    {
        $args = [
            [
                'id'       => 'rrze_search_resources',
                'title'    => __('Search Engines', 'rrze-search'),
                'callback' => [$this->callbacksOptions, 'enginesConfigure'],
                'page'     => 'rrze_search_su',
                'section'  => 'rrze_search_super_admin_section',
                'args'     => [
                    'option_name' => 'rrze_search_settings',
                    'label_for'   => 'rrze_search_resources',
                ]
            ],
            [
                'id'       => 'rrze_search_engines',
                'title'    => __('Search Engines', 'rrze-search'),
                'callback' => [$this->callbacksOptions, 'enginesToggle'],
                'page'     => 'rrze_search',
                'section'  => 'rrze_search_admin_section',
                'args'     => [
                    'option_name' => 'rrze_search_settings',
                    'label_for'   => 'rrze_search_engines'
                ]
            ],
            [
                'id'       => 'rrze_search_page_id',
                'title'    => __('Search Results Page', 'rrze-search'),
                'callback' => [$this->callbacksOptions, 'resultsPage'],
                'page'     => 'rrze_search_su',
                'section'  => 'rrze_search_super_admin_section',
                'args'     => [
                    'option_name' => 'rrze_search_settings',
                    'label_for'   => 'rrze_search_page_id', // matches ID
                ]
            ]
        ];
        $this->settings->setFields($args);
    }
}