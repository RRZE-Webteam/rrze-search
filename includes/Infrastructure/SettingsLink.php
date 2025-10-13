<?php

namespace RRZE\RRZESearch\Infrastructure;

use RRZE\RRZESearch\Application\Controller\AppController;

/**
 * Settings Link
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class SettingsLink extends AppController
{
    /**
     * Registration
     */
    public function register()
    {
        add_filter('plugin_action_links_'.$this->plugin, array($this, 'dashboardLink'));
    }

    /**
     * Add the dashboard link
     *
     * @param array $links Existing links
     *
     * @return array Amended links
     */
    public function dashboardLink($links)
    {
        $links[] = '<a href="admin.php?page=rrze_search">'.__('Settings', 'rrze-search').'</a>';

        return $links;
    }
}
