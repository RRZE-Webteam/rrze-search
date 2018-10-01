<?php

namespace RRZE\RRZESearch\Infrastructure\Persistence;

use RRZE\RRZESearch\Application\Controller\AppController;

class OptionsPanels extends AppController
{
    public $settings;
    public $resources = '';

    public function adminDashboard()
    {
        return require($this->plugin_path.'RRZESearch'.DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'admin-dashboard.php');
    }

    public function superAdminDashboard()
    {
        return require($this->plugin_path.'RRZESearch'.DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'admin-dashboard-super.php');
    }
}