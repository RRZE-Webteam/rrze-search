<?php

namespace RRZE\RRZESearch\Application;

use RRZE\RRZESearch\Application\Shortcode\SearchResultsShortcode;

class ShortcodeController extends AppController
{
    public function register()
    {
        /**
         * TODO: Add condition to validate registration
         */
        $search_results_shortcode = new SearchResultsShortcode();
        $search_results_shortcode->register();
    }
}