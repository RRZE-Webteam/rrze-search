<?php

namespace RRZE\RRZESearch\Infrastructure;

use RRZE\RRZESearch\Application\AppController;
use RRZE\RRZESearch\Infrastructure\Callback\AdminCallbacks;
use RRZE\RRZESearch\Infrastructure\Callback\OptionsCallbacks;

class Dashboard extends AppController
{
    public $settings;
    public $callbacks;
    public $callbacks_options;
    public $pages = array();

    public function __construct()
    {
        /**
         * TODO determine if parent construct is needed
         * parent::__construct();
         */
        $this->settings          = new SettingsApi();
        $this->callbacks         = new AdminCallbacks();
        $this->callbacks_options = new OptionsCallbacks();
    }

    public function register()
    {
        $this->setAdminPage();
        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this->settings->addPages($this->pages)->register();
    }

    /**
     * Configure the admin page
     */
    public function setAdminPage()
    {
        $this->pages = array(
            array(
                'page_title' => 'Einstellungen › Suche',
                'menu_title' => 'RRZE Suche',
                'capability' => 'manage_options',
                'menu_slug'  => 'rrze_search',
                'callback'   => array($this->callbacks, 'adminDashboard'),
            )
        );
    }

    public function setSettings()
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
    public function setSections()
    {
        $args = array(
            array(
                'id'       => 'rrze_search_admin_section',
                'title'    => 'Einstellungen › Suche',
                'callback' => array($this->callbacks_options, 'printAdminSection'),
                'page'     => 'rrze_search'
            )
        );
        $this->settings->setSections($args);
    }

    /**
     * Configure the fields
     */
    public function setFields()
    {
        $args = array(
            array(
                'id'       => 'rrze_search_resources',
                'title'    => 'Verfügbare Suchmaschinen',
                'callback' => array($this->callbacks_options, 'resourcesTable'),
                'page'     => 'rrze_search',
                'section'  => 'rrze_search_admin_section',
                'args'     => array(
                    'option_name' => 'rrze_search_settings',
                    'label_for'   => 'rrze_search_resources',
                )
            ),
            array(
                'id'       => 'rrze_search_disclaimer',
                'title'    => 'Disclaimer Link',
                'callback' => array($this->callbacks_options, 'disclaimerDropDown'),
                'page'     => 'rrze_search',
                'section'  => 'rrze_search_admin_section',
                'args'     => array(
                    'option_name' => 'rrze_search_settings',
                    'label_for'   => 'rrze_search_disclaimer', // matches ID
                    'options'     => $this->settings->getPosts(),
                )
            ),
            array(
                'id'       => 'rrze_search_page_id',
                'title'    => 'Search Results Page',
                'callback' => array($this->callbacks_options, 'disabledInput'),
                'page'     => 'rrze_search',
                'section'  => 'rrze_search_admin_section',
                'args'     => array(
                    'option_name' => 'rrze_search_settings',
                    'label_for'   => 'rrze_search_page_id', // matches ID
                )
            )
        );

        $this->settings->setFields($args);
    }
}