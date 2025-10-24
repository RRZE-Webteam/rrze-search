<?php

namespace RRZE\RRZESearch\Infrastructure\Settings;
use JetBrains\PhpStorm\NoReturn;

defined( 'ABSPATH' ) || exit;

/**
 * Lightweight wrapper around the WordPress Settings API for RRZE Search.
 *
 * Encapsulates menu registration, section and field wiring, and AJAX utilities
 * so higher-level controllers can describe configuration screens declaratively.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class SettingsApi
{
    /**
     * Definition of top-level admin pages handled by the settings API.
     *
     * @var array<int, array<string, mixed>>
     */
    protected $adminPages = [];
    /**
     * Definition of submenu entries that extend the primary RRZE Search page.
     *
     * @var array<int, array<string, mixed>>
     */
    protected $adminSubpages = [];
    /**
     * `register_setting` payloads registered during `admin_init`.
     *
     * @var array<int, array<string, mixed>>
     */
    protected $settings = [];
    /**
     * Section definitions wired via `add_settings_section`.
     *
     * @var array<int, array<string, mixed>>
     */
    protected $sections = [];
    /**
     * Field definitions registered with `add_settings_field`.
     *
     * @var array<int, array<string, mixed>>
     */
    protected $fields = [];

    /**
     * Hooks WordPress actions required to expose menus, settings, and AJAX.
     *
     * @return void
     */
    public function register(): void
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
     * Stores the top-level admin pages to be registered on `admin_menu`.
     *
     * @param array<int, array<string, mixed>> $pages Menu configuration entries.
     *
     * @return self
     */
    public function addPages(array $pages): self
    {
        $this->adminPages = $pages;

        return $this;
    }

    /**
     * Mirrors the first admin page into a submenu to provide a default entry.
     *
     * @param string|null $title Optional override for the submenu title.
     *
     * @return self
     */
    public function withSubPage(?string $title = null): self
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
     * Appends additional submenu definitions to the stored configuration.
     *
     * @param array<int, array<string, mixed>> $pages Subpage configuration entries.
     *
     * @return self
     */
    public function addSubPages(array $pages): self
    {
        $this->adminSubpages = array_merge($this->adminSubpages, $pages);

        return $this;
    }

    /**
     * Registers stored admin pages and subpages with WordPress.
     *
     * @return void
     */
    public function addAdminMenu(): void
    {
        foreach ($this->adminPages as $page) {
            add_menu_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'],  $page['callback'], $page['icon_url']);
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
     * Persists `register_setting` arguments for later registration.
     *
     * @param array<int, array<string, mixed>> $settings Settings API definitions.
     *
     * @return self
     */
    public function setSettings(array $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Persists section definitions for WordPress registration.
     *
     * @param array<int, array<string, mixed>> $sections Section configuration entries.
     *
     * @return self
     */
    public function setSections(array $sections): self
    {
        $this->sections = $sections;

        return $this;
    }

    /**
     * Persists field definitions for WordPress registration.
     *
     * @param array<int, array<string, mixed>> $fields Field configuration entries.
     *
     * @return self
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Registers settings, sections, and fields with the WordPress Settings API.
     *
     * @return void
     */
    public function registerCustomFields(): void
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
     * Handles AJAX requests for removing a Search Engine resource entry.
     *
     * Expects `resource_id` in the POST body and echoes the update result as JSON.
     *
     * @return void
     */
    #[NoReturn] public function resourceRemoval(): void
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
        $resources = (count($resources) > 0) ? $resources : ' ';

        $update = update_option($optionName, [
            'rrze_search_resources' => $resources,
            'rrze_search_engines' => $option['rrze_search_engines'],
            'rrze_search_page_id' => $option['rrze_search_page_id'],
        ], 'yes');

        echo json_encode($update);
        die();
    }
}
