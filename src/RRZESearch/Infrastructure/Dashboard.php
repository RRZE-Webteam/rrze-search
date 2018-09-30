<?php

namespace RRZE\RRZESearch\Infrastructure;

use RRZE\RRZESearch\Application\Controller\AppController;
use RRZE\RRZESearch\Infrastructure\Persistence\OptionsCallbacks;
use RRZE\RRZESearch\Infrastructure\Persistence\OptionsPanels;
use RRZE\RRZESearch\Infrastructure\Persistence\SettingsApi;

class Dashboard extends AppController
{
    public $settings;
    public $callbacks;
    public $callbacks_options;
    public $pages = array();
    public $subpages = array();

    public function __construct()
    {
        parent::__construct();
        $this->settings          = new SettingsApi();
        $this->callbacks         = new OptionsPanels();
        $this->callbacks_options = new OptionsCallbacks();
    }

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

        /**
         * Option Admin Page aka Dashboard
         */
        $this->pages = array(
            array(
                'page_title' => __('Settings › Search', 'rrze-search'),
                'menu_title' => 'RRZE Suche',
                'capability' => 'manage_options',
                'menu_slug'  => $slug,
                'callback'   => array($this->callbacks, 'adminDashboard'),
                'icon_url'   => 'dashicons-search'
            )
        );

        /**
         * Sub Page is used for the Super Admin Functionality
         */
        $this->subpages = array(
            array(
                'parent_slug' => 'rrze_search',
                'page_title'  => __('Settings › Search', 'rrze-search'),
                'menu_title'  => __('Configuration', 'rrze-search'),
                'capability'  => 'manage_options',
                'menu_slug'   => $slug.'_su',
                'callback'    => array($this->callbacks, 'superAdminDashboard')
            )
        );
    }

    public function setSettings(): void
    {
        /**
         * In relation to register_setting() from the WP Settings API
         */
        $args = array(
            array(
                'option_group' => 'rrze_search_settings',
                'option_name'  => 'rrze_search_settings',
                'callback'     => array($this->callbacks_options, 'sanitize')
            )
        );

        $this->settings->setSettings($args);
    }

    /**
     * Configure the settings
     */
    public function setSections(): void
    {
        $args = array(
            array(
                'id'       => 'rrze_search_admin_section',
                'title'    => __('Settings › Search', 'rrze-search'),
                'callback' => array($this->callbacks_options, 'printAdminSection'),
                'page'     => 'rrze_search'
            ),
            array(
                'id'       => 'rrze_search_super_admin_section',
                'title'    => __('Settings › Search', 'rrze-search'),
                'callback' => array($this->callbacks_options, 'printSuperAdminSection'),
                'page'     => 'rrze_search_su'
            )
        );
        $this->settings->setSections($args);
    }

    /**
     * Configure the fields
     */
    public function setFields(): void
    {
        $args = array(
            array(
                'id'       => 'rrze_search_resources',
                'title'    => __('Search Engines', 'rrze-search'),
                'callback' => array($this->callbacks_options, 'resourcesTable'),
                'page'     => 'rrze_search_su',
                'section'  => 'rrze_search_super_admin_section',
                'args'     => array(
                    'option_name' => 'rrze_search_settings',
                    'label_for'   => 'rrze_search_resources',
                )
            ),
            array(
                'id'       => 'rrze_search_engines',
                'title'    => __('Search Engines', 'rrze-search'),
                'callback' => array($this->callbacks_options, 'enginesTable'),
                'page'     => 'rrze_search',
                'section'  => 'rrze_search_admin_section',
                'args'     => array(
                    'option_name' => 'rrze_search_settings',
                    'label_for'   => 'rrze_search_engines'
                )
            ),
            array(
                'id'       => 'rrze_search_page_id',
                'title'    => __('Search Results Page', 'rrze-search'),
                'callback' => array($this->callbacks_options, 'disabledInput'),
                'page'     => 'rrze_search_su',
                'section'  => 'rrze_search_super_admin_section',
                'args'     => array(
                    'option_name' => 'rrze_search_settings',
                    'label_for'   => 'rrze_search_page_id', // matches ID
                )
            )
        );
        $this->settings->setFields($args);
    }
}