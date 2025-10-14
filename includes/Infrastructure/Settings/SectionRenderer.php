<?php

namespace RRZE\RRZESearch\Infrastructure\Settings;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Application\Controller\AppController;

/**
 * Outputs section descriptions for the RRZE Search settings screens.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Infrastructure
 */
class SectionRenderer extends AppController
{
    /**
     * Prints the section intro for regular administrators managing engines.
     *
     * @return void
     */
    public function printAdminSection(): void
    {
        echo __('Enable Search Engines', 'rrze-search');
    }

    /**
     * Prints the section intro targeted at super administrators configuring engines.
     *
     * @return void
     */
    public function printSuperAdminSection(): void
    {
        echo __('Configure Search Engines', 'rrze-search');
    }
}
