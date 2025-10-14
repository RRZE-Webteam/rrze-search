<?php

namespace RRZE\RRZESearch\Infrastructure\Persistence;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Application\Controller\AppController;

/**
 * Renders the RRZE Search admin menu and super admin menu setting templates.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class SettingsViews extends AppController
{
    /**
     * Renders the admin settings page template for regular RRZE Search settings.
     *
     * @return string|null Included template result.
     */
    public function adminDashboard(): ?string
    {
        return require $this->pluginPath . 'RRZESearch' . DIRECTORY_SEPARATOR . 'Infrastructure' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'admin-dashboard.php';
    }

    /**
     * Renders the super admin settings page template for search configuration.
     *
     * @return string|null Included template result.
     */
    public function superAdminDashboard(): ?string
    {
        return require $this->pluginPath . 'RRZESearch' . DIRECTORY_SEPARATOR . 'Infrastructure' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'admin-dashboard-super.php';
    }
}
