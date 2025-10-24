<?php

namespace RRZE\RRZESearch\Infrastructure;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Application\Controller\AppController;

/**
 * Adds a quick link from the plugins list to the RRZE Search settings page.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class SettingsLink extends AppController
{
    /**
     * Registers the plugin action link filter for the RRZE Search settings page.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter('plugin_action_links_' . $this->plugin, [$this, 'dashboardLink']);
    }

    /**
     * Appends the RRZE Search settings link to the plugin row actions.
     *
     * @param array<int, string> $links Existing action links for the plugin row.
     *
     * @return array<int, string> Modified action links including the settings URL.
     */
    public function dashboardLink(array $links): array
    {
        $links[] = '<a href="admin.php?page=rrze_search">' . __('Settings', 'rrze-search') . '</a>';

        return $links;
    }
}
