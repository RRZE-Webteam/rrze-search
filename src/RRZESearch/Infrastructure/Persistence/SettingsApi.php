<?php

namespace RRZE\RRZESearch\Infrastructure\Persistence;

use RRZE\RRZESearch\Infrastructure\Database\DatabaseApi;

/**
 * Settings API
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class SettingsApi
{
    /**
     * Admin pages
     *
     * @var array
     */
    protected $adminPages = [];
    /**
     * Admin subpages
     *
     * @var array
     */
    protected $adminSubpages = [];
    /**
     * Settings
     *
     * @var array
     */
    protected $settings = [];
    /**
     * Sections
     *
     * @var array
     */
    protected $sections = [];
    /**
     * Fields
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = new DatabaseApi();
    }

    /**
     * Register the admin menu and settings
     */
    public function register()
    {
        if (!empty($this->adminPages) || !empty($this->adminSubpages)) {
            add_action('admin_menu', array($this, 'addAdminMenu'));
        }
        if (!empty($this->settings)) {
            add_action('admin_init', array($this, 'registerCustomFields'));
            add_action('wp_ajax_resourceRemoval', array($this, 'resourceRemoval'));
        }
    }

    /**
     * Add the admin pages
     *
     * @param array $pages
     *
     * @return $this
     */
    public function addPages(array $pages)
    {
        $this->adminPages = $pages;

        return $this;
    }

    /**
     * ??
     *
     * @param string|null $title
     *
     * @return SettingsApi Self reference
     */
    public function withSubPage(string $title = null)
    {
        if (empty($this->adminPages)) {
            return $this;
        }
        $adminPage           = $this->adminPages[0];
        $subpage             = [
            [
                'parent_slug' => $adminPage['menu_slug'],
                'page_title'  => $adminPage['page_title'],
                'menu_title'  => ($title) ? $title : $adminPage['menu_title'],
                'capability'  => $adminPage['capability'],
                'menu_slug'   => $adminPage['menu_slug'],
                'callback'    => $adminPage['callback']
            ]
        ];
        $this->adminSubpages = $subpage;

        return $this;
    }

    /**
     * Add the supages
     *
     * @param array $pages Subpages
     *
     * @return SettingsApi Self reference
     */
    public function addSubPages(array $pages)
    {
        $this->adminSubpages = array_merge($this->adminSubpages, $pages);

        return $this;
    }

    /**
     * Add pages to the admin menu
     */
    public function addAdminMenu()
    {
        foreach ($this->adminPages as $page) {
            add_menu_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'],
                $page['callback'], $page['icon_url'], $page['position']);
        }

        global $current_user;
        $user_roles = $current_user->roles;
        array_shift($user_roles);

        if (is_super_admin($current_user->ID)) {
            foreach ($this->adminSubpages as $page) {
                add_submenu_page($page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'],
                    $page['menu_slug'], $page['callback']);
            }
        }
    }

    /**
     * Set the settings
     *
     * @param array $settings Settings
     *
     * @return SettingsApi Self reference
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Set the sections
     *
     * @param array $sections Sections
     *
     * @return SettingsApi Self reference
     */
    public function setSections(array $sections)
    {
        $this->sections = $sections;

        return $this;
    }

    /**
     * Set the fields
     *
     * @param array $fields
     *
     * @return SettingsApi Self reference
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Register custom fields
     */
    public function registerCustomFields()
    {
        // Register Setting
        foreach ($this->settings as $setting) {
            register_setting($setting["option_group"], $setting["option_name"],
                (isset($setting["callback"]) ? $setting["callback"] : ''));
        }
        // Add settings section
        foreach ($this->sections as $section) {
            add_settings_section($section["id"], $section["title"],
                (isset($section["callback"]) ? $section["callback"] : ''), $section["page"]);
        }
        // Add settings field
        foreach ($this->fields as $field) {
            add_settings_field($field["id"], $field["title"], (isset($field["callback"]) ? $field["callback"] : ''),
                $field["page"], $field["section"], (isset($field["args"]) ? $field["args"] : ''));
        }
    }

    /**
     * Remove a resource
     */
    public function resourceRemoval()
    {
        $resources   = [];
        $index       = $_POST['resource_id'];
        $optionName  = 'rrze_search_settings';
        $option      = get_option($optionName);
        $optionValue = $option['rrze_search_resources'];

        foreach ($optionValue as $key => $value) {
            if ($key !== (int)$index) {
                $resources[] = $value;
            }
        }
        $resources = (count($resources) > 0) ? $resources : 'empty';

        $update = update_option($optionName, [
            'rrze_search_resources' => $resources,
            'rrze_search_engines' => $option['rrze_search_engines'],
            'rrze_search_page_id' => $option['rrze_search_page_id'],
        ], 'yes');

        echo json_encode($update);
        die();
    }
}