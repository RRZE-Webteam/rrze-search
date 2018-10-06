<?php

namespace RRZE\RRZESearch\Application\Controller;

use RRZE\RRZESearch\Application\Shortcode\ResultsShortcode;

/**
 * Shortcode controller
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Application
 */
class ShortcodeController extends AppController
{
    /**
     * Register a new shortcode
     *
     * @todo Add condition to validate registration
     */
    public function register()
    {
        $search_results_shortcode = new ResultsShortcode();
        $search_results_shortcode->register();
    }
}