<?php

namespace RRZE\RRZESearch\Application;

use RRZE\RRZESearch\Application\Widgets\SearchWidget;

class WidgetController extends AppController
{
    public function register()
    {
        if ($this->activated('rrze_search')) return;
        $search_widget = new SearchWidget();
        $search_widget->register();
    }
}