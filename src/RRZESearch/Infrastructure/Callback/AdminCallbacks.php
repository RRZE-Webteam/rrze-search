<?php

namespace RRZE\RRZESearch\Infrastructure\Callback;

use RRZE\RRZESearch\Application\AppController;

class AdminCallbacks extends AppController
{
    public $settings;
    public $resources = '';

    public function adminDashboard()
    {
        return require($this->plugin_path.'RRZESearch'.DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'admin-dashboard.php');
    }
}