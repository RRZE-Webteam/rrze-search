<?php

namespace RRZE\RRZESearch\Application\Controller;

use RRZE\RRZESearch\Application\Widget\SearchWidget;

/**
 * Widget controller
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Application\Controller
 */
class WidgetController extends AppController
{
    /**
     * One-time registration of the widget
     */
    public function register()
    {
        if (!$this->activated('rrze_search')) {
            $search_widget = new SearchWidget();
            $search_widget->register();
        }
    }
}