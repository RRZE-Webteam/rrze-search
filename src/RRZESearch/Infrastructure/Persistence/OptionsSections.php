<?php

namespace RRZE\RRZESearch\Infrastructure\Persistence;

use RRZE\RRZESearch\Application\Controller\AppController;

/**
 * Options Sections
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class OptionsSections extends AppController
{
    /**
     * Print the admin section
     */
    public function printAdminSection(): void
    {
        echo __('Enable Search Engines', 'rrze-search');
    }

    /**
     * Print the superadmin section
     */
    public function printSuperAdminSection(): void
    {
        echo __('Configure Search Engines', 'rrze-search');
    }
}