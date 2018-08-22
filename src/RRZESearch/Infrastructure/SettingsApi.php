<?php

namespace RRZE\RRZESearch\Infrastructure;

/**
 * Class SettingsApi
 * @package RRZE\RRZESearch\Domain
 */
class SettingsApi
{
    public $admin_pages = array();
    public $admin_subpages = array();
    public $settings = array();
    public $sections = array();
    public $fields = array();

    public function __construct()
    {
        $this->db = new DatabaseApi();
    }

    public function register()
    {
        if (!empty($this->admin_pages)) {
            add_action('admin_menu', array($this, 'addAdminMenu'));
        }
        if (!empty($this->settings)) {
            add_action('admin_init', array($this, 'registerCustomFields'));
            add_action('wp_ajax_resourceRemoval', array($this, 'resourceRemoval'));
        }
    }

    public function addPages(array $pages)
    {
        $this->admin_pages = $pages;

        return $this;
    }

    public function addAdminMenu()
    {
        foreach ($this->admin_pages as $page) {
            add_options_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'],
                $page['callback']);
        }
    }

    public function setSettings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    public function setSections(array $sections)
    {
        $this->sections = $sections;

        return $this;
    }

    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function registerCustomFields()
    {
        /**
         * Register Setting
         */
        foreach ($this->settings as $setting) {
            register_setting($setting["option_group"], $setting["option_name"],
                (isset($setting["callback"]) ? $setting["callback"] : ''));
        }
        // add settings section
        foreach ($this->sections as $section) {
            add_settings_section($section["id"], $section["title"],
                (isset($section["callback"]) ? $section["callback"] : ''), $section["page"]);
        }
        // add settings field
        foreach ($this->fields as $field) {
            add_settings_field($field["id"], $field["title"], (isset($field["callback"]) ? $field["callback"] : ''),
                $field["page"], $field["section"], (isset($field["args"]) ? $field["args"] : ''));
        }
    }

    /**
     * Additional function.
     */
    public function resourceRemoval()
    {
        $resources    = [];
        $index        = $_POST['resource_id'];
        $option_name  = 'rrze_search_settings';
        $option       = get_option($option_name);
        $option_value = $option['rrze_search_resources'];
        unset($option_value[$index]);

        foreach ($option_value as $item) {
            $resources[] = $item;
        }

        $option['rrze_search_resources'] = $resources;

        $update = update_option($option_name, $option);
        echo json_encode($update);
        die();
    }

    public function getPosts()
    {
        $posts = $this->db->get_posts();

        return $posts;
    }

    /**
     * mysql filter
     * SELECT * FROM `wp_options` WHERE `option_name` LIKE 'rrze_search_settings'
     */
}