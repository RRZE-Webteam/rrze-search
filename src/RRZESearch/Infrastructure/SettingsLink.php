<?php

namespace RRZE\RRZESearch\Infrastructure;

use RRZE\RRZESearch\Application\AppController;

class SettingsLink extends AppController
{
    public function register()
    {
        add_filter( 'plugin_action_links_'.$this->plugin, array( $this, 'dashboard_link' ) );
    }

    public function dashboard_link($links)
    {
        $filter_link = '<a href="options-general.php?page=rrze_search">'.__('Settings', 'rrze-search').'</a>';
        $links[]     = $filter_link;
        return $links;
    }
}
