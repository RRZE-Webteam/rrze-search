<?php

namespace RRZE\RRZESearch\Infrastructure;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Application\Controller\AppController;
use RRZE\RRZESearch\Infrastructure\Persistence\OptionFieldRenderer;
use RRZE\RRZESearch\Infrastructure\Persistence\SettingsViews;
use RRZE\RRZESearch\Infrastructure\Persistence\SectionRenderer;
use RRZE\RRZESearch\Infrastructure\Persistence\SettingsSanitizer;
use RRZE\RRZESearch\Infrastructure\Persistence\SettingsApi;

/**
 * Coordinates the RRZE Search admin dashboard integration with WordPress.
 *
 * The dashboard controller builds the plugin's settings pages, sections, and
 * fields, delegating the rendering and sanitization to the infrastructure
 * persistence layer while relying on the Settings API wrapper.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class SettingsPage extends AppController
{
    /**
     * Settings API helper responsible for registering menu pages and options.
     *
     * @var SettingsApi
     */
    protected $settings;

    /**
     * Field callbacks that render the individual settings inputs.
     *
     * @var OptionFieldRenderer
     */
    protected $callbacksFields;
    /**
     * Page callbacks that output the SettingsPage screens.
     *
     * @var SettingsViews
     */
    protected $callbacksPages;
    /**
     * Section callbacks that add explanatory content to settings sections.
     *
     * @var SectionRenderer
     */
    protected $callbacksSections;
    /**
     * Settings callbacks that sanitize submitted option values.
     *
     * @var SettingsSanitizer
     */
    protected $callbackSettings;
    /**
     * Definitions for the primary admin pages consumed by the settings API.
     *
     * @var array<int, array<string, mixed>>
     */
    protected $pages = [];
    /**
     * Definitions for subpages that extend the primary menu.
     *
     * @var array<int, array<string, mixed>>
     */
    protected $subpages = [];

    /**
     * Prepares the settings API helpers and callback dependencies.
     */
    public function __construct()
    {
        parent::__construct();
        $this->settings          = new SettingsApi();
        $this->callbacksFields   = new OptionFieldRenderer();
        $this->callbacksPages    = new SettingsViews();
        $this->callbacksSections = new SectionRenderer();
        $this->callbackSettings  = new SettingsSanitizer();
    }

    /**
     * Registers admin pages, sections, and fields with the Settings API.
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
     * Builds the menu and submenu configuration arrays for the Admin Menu inside the WP Dashboard.
     */
    public function pagesConfiguration(): void
    {
        $slug = 'rrze_search';

        // Option Admin Page
        $this->pages = [
            [
                'page_title' => __('Settings › Search', 'rrze-search'),
                'menu_title' => 'RRZE Suche',
                'capability' => 'manage_options',
                'menu_slug'  => $slug,
                'callback'   => [$this->callbacksPages, 'adminDashboard'],
                'icon_url'   => 'dashicons-search'
            ]
        ];

        // Sub Page used for the Super Admin Functionality
        $this->subpages = [
            [
                'parent_slug' => 'rrze_search',
                'page_title'  => __('Settings › Search', 'rrze-search'),
                'menu_title'  => __('Configuration', 'rrze-search'),
                'capability'  => 'manage_options',
                'menu_slug'   => $slug.'_su',
                'callback'    => [$this->callbacksPages, 'superAdminDashboard']
            ]
        ];
    }

    /**
     * Registers the RRZE Search option with WordPress and assigns sanitizers.
     */
    public function setSettings(): void
    {
        // In relation to register_setting() from the WP Settings API
        $args = [
            [
                'option_group' => 'rrze_search_settings',
                'option_name'  => 'rrze_search_settings',
                'callback'     => [$this->callbackSettings, 'sanitize']
            ]
        ];

        $this->settings->setSettings($args);
    }

    /**
     * Defines sections that group settings inputs on admin and super admin pages.
     */
    public function setSections(): void
    {
        $args = [
            [
                'id'       => 'rrze_search_admin_section',
                'title'    => __('Settings › Search', 'rrze-search'),
                'callback' => [$this->callbacksSections, 'printAdminSection'],
                'page'     => 'rrze_search'
            ],
            [
                'id'       => 'rrze_search_super_admin_section',
                'title'    => __('Settings › Search', 'rrze-search'),
                'callback' => [$this->callbacksSections, 'printSuperAdminSection'],
                'page'     => 'rrze_search_su'
            ]
        ];
        $this->settings->setSections($args);
    }

    /**
     * Declares the individual fields displayed within each settings section.
     */
    public function setFields(): void
    {
        $args = [
            [
                'id'       => 'rrze_search_resources',
                'title'    => __('Search Engines', 'rrze-search'),
                'callback' => [$this->callbacksFields, 'enginesConfigure'],
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
                'callback' => [$this->callbacksFields, 'enginesToggle'],
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
                'callback' => [$this->callbacksFields, 'resultsPage'],
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
