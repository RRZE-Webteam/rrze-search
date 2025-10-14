<?php

namespace RRZE\RRZESearch\Application\Shortcode;
defined( 'ABSPATH' ) || exit;

use RRZE\RRZESearch\Domain\Contract\Engine;

/**
 * Renders the RRZE Search results via the `[rrze_search_results]` shortcode.
 *
 * Resolves the requested engine, executes the remote query, and loads the
 * appropriate template while handling common error states.
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Application
 */
class ResultsShortcode
{
    /**
     * Cached RRZE Search plugin options.
     *
     * @var array<string, mixed>
     */
    public $options;

    /**
     * Engine instance executing the search query for the current request.
     *
     * @var Engine|null
     */
    public $searchEngine;

    /**
     * Loads plugin options used during shortcode execution.
     */
    public function __construct()
    {
        $this->options = get_option('rrze_search_settings');
    }

    /**
     * Registers the shortcode with WordPress.
     *
     * @return void
     */
    public function register(): void
    {
        add_shortcode('rrze_search_results', [$this, 'shortcodeInit']);
    }

    /**
     * Handles the shortcode rendering lifecycle for search results.
     *
     * Determines the active engine from query parameters, runs the search, and
     * includes the matching template or error view.
     *
     * @return void
     */
    public function shortcodeInit(): void
    {
        $engines      = $this->options['rrze_search_engines'];
        $resources    = $this->options['rrze_search_resources'];
        $pageLink     = get_permalink($this->options['rrze_search_page_id']);
        $templatesDir = DIRECTORY_SEPARATOR.'Infrastructure'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR;

        $query = '';

        if (isset($_GET['q'])) {
            $query = sanitize_text_field(wp_unslash($_GET['q']));
        } elseif (isset($_GET['s'])) {
            $query = sanitize_text_field(wp_unslash($_GET['s']));
        }
        $useengine = 0;
        if (isset($_GET['se'])) {
            $useengine  = absint($_GET['se']);
        }

        if ((isset($useengine)) && (isset($resources[$useengine]))) {
            $resource     = $resources[$useengine];
        }

        if (empty($query)) {
            // Render the Search Engine Results
            include \dirname(__DIR__, 2).$templatesDir.'Results'.DIRECTORY_SEPARATOR.'Error-NoQuery.php';
        } elseif (isset($resource)) {
            $startPage    = 1;
            if ((isset($_GET['start'])) && (absint($_GET['start']) > 0)) {
                $startPage = absint($_GET['start']);
            }

            $availableEngines = $engines;
            $preferredEngine  = (string)$useengine;
            $currentQuery     = $query;

            $this->searchEngine = new $resource['resource_class'];
            $searchEngineClass  = substr(strrchr(get_parent_class($this->searchEngine), '\\'), 1);

            $queryResults = $this->searchEngine->query($query, $resource['args'], $startPage);
            $results      = is_array($queryResults) ? $queryResults : json_decode($queryResults, true);

            $currentEngineKey    = $useengine;
            $currentEngineConfig = $resource;

            if ((isset($results['error'])) && ($results['error']['code']>=400)) {
                include \dirname(__DIR__, 2).$templatesDir.'Results'.DIRECTORY_SEPARATOR.'Error-shortcode.php';
            }  else {
                include \dirname(__DIR__, 2).$templatesDir.'Results'.DIRECTORY_SEPARATOR.$searchEngineClass.'-shortcode.php';
                include \dirname(__DIR__, 2).$templatesDir.'search-pagination.php';
            }

        } else {
            include \dirname(__DIR__, 2).$templatesDir.'Results'.DIRECTORY_SEPARATOR.'Error-shortcode.php';
        }
    }
}
