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
     */
    public function register()
    {
        if (!$this->activated('rrze_search')) {
            $search_results_shortcode = new ResultsShortcode();
            $search_results_shortcode->register();
        }
    }
}