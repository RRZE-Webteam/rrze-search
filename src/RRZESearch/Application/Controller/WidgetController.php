<?php

namespace RRZE\RRZESearch\Application\Controller;

use RRZE\RRZESearch\Application\Widget\SearchWidget;

class WidgetController extends AppController
{
    public function register()
    {
        if ($this->activated('rrze_search')) return;
        $search_widget = new SearchWidget();
        $search_widget->register();
    }
}