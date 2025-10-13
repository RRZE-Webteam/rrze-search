<?php

namespace RRZE\RRZESearch\Infrastructure\Persistence;

use RRZE\RRZESearch\Application\Controller\AppController;

/**
 * Options Pages
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class OptionsPages extends AppController
{
    /**
     * Settings
     *
     * @var array
     */
    public $settings;
    /**
     * Resources
     *
     * @var string
     */
    public $resources = '';

    /**
     * Render the admin dashboard
     *
     * @return string Admin dashboard
     */
    public function adminDashboard()
    {
        return require($this->pluginPath.'RRZESearch'.DIRECTORY_SEPARATOR.'Infrastructure'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'admin-dashboard.php');
    }

    /**
     * Render the superadmin dashboard
     *
     * @return string Superadmin dashboard
     */
    public function superAdminDashboard()
    {
        return require($this->pluginPath.'RRZESearch'.DIRECTORY_SEPARATOR.'Infrastructure'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'admin-dashboard-super.php');
    }
}