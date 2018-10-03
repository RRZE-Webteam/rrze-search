<?php

namespace RRZE\RRZESearch\Infrastructure\Persistence;

use RRZE\RRZESearch\Application\Controller\AppController;

/**
 * Options Panels
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class OptionsPanels extends AppController
{
    /**
     * Settings
     *
     * @var
     * @todo Data type? Really public?
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
        return require($this->pluginPath.'RRZESearch'.DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'admin-dashboard.php');
    }

    /**
     * Render the superadmin dashboard
     *
     * @return string Superadmin dashboard
     */
    public function superAdminDashboard()
    {
        return require($this->pluginPath.'RRZESearch'.DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'admin-dashboard-super.php');
    }
}