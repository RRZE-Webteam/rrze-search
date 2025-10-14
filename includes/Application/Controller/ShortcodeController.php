<?php

namespace RRZE\RRZESearch\Application\Controller;

use RRZE\RRZESearch\Application\Shortcode\ResultsShortcode;

/**
 * Registers the results shortcode when the plugin is active.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Application
 */
class ShortcodeController extends AppController
{
    /**
     * Hooks the `[rrze_search_results]` shortcode into WordPress.
     *
     * @return void
     */
    public function register(): void
    {
        if (!$this->activated('rrze_search')) {
            $search_results_shortcode = new ResultsShortcode();
            $search_results_shortcode->register();
        }
    }
}
