<?php

namespace RRZE\RRZESearch\Application\Controller;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Application\Widget\SearchWidget;

/**
 * Bootstraps the RRZE Search widget when the plugin is active.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Application\Controller
 */
class WidgetController extends AppController
{
    /**
     * Registers the RRZE Search widget unless another instance is active.
     *
     * @return void
     */
    public function register(): void
    {
        if (!$this->activated('rrze_search')) {
            $search_widget = new SearchWidget();
            $search_widget->register();
        }
    }
}
