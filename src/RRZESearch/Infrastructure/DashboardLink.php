<?php

namespace RRZE\RRZESearch\Infrastructure;

use RRZE\RRZESearch\Application\AppController;

class DashboardLink extends AppController
{
    public function register()
    {
        add_filter( 'plugin_action_links_'.$this->plugin, array( $this, 'dashboard_link' ) );
    }

    public function dashboard_link($links)
    {
        $filter_link = '<a href="options-general.php?page=rrze_search">Settings</a>';
        array_push($links, $filter_link);
        return $links;
    }
}
