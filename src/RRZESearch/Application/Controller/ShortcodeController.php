<?php

namespace RRZE\RRZESearch\Application\Controller;

use RRZE\RRZESearch\Application\Shortcode\ResultsShortcode;

class ShortcodeController extends AppController
{
    public function register()
    {
        /**
         * TODO: Add condition to validate registration
         */
        $search_results_shortcode = new ResultsShortcode();
        $search_results_shortcode->register();
    }
}