<?php

namespace RRZE\RRZESearch\Application\Shortcode;

/**
 * Search Results Shortcode
 *
 * @package    RRZE\RRZESearch
 * @subpackage RRZE\RRZESearch\Application
 */
class ResultsShortcode
{
    /**
     * Results options
     *
     * @var array
     */
    public $options;

    public $searchEngine;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->options = get_option('rrze_search_settings');
    }

    /**
     * Register an associated shortcode
     */
    public function register()
    {
        add_shortcode('rrze_search_results', array($this, 'shortcodeInit'));
    }

    /**
     * Shortcode initialization
     */
    public function shortcodeInit()
    {
        $engines      = $this->options['rrze_search_engines'];
        $resources    = $this->options['rrze_search_resources'];
        $query        = $_GET['q'];
        $startPage    = $_GET['start'] ?? '1';
        $resource     = $this->options['rrze_search_resources'][$_GET['se']];
        $pageLink     = get_permalink($this->options['rrze_search_page_id']);
        $templatesDir = DIRECTORY_SEPARATOR.'Infrastructure'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR;

        // Define the Search Engine Resource & class name
        $this->searchEngine = new $resource['resource_class'];
        $searchEngineClass  = substr(strrchr(get_parent_class($this->searchEngine), '\\'), 1);
        echo $searchEngineClass;

        // Finalize Results
        $queryResults = $this->searchEngine->query($query, $resource['resource_key'], $startPage);
        $results      = is_array($queryResults) ? $queryResults : json_decode($queryResults, true);

        // Render the Search Engine Tabs
        include \dirname(__DIR__, 2).$templatesDir.'search-tabs.php';

        // Render the Search Engine Results
        include \dirname(__DIR__, 2).$templatesDir.'Results'.DIRECTORY_SEPARATOR.$searchEngineClass.'-shortcode.php';
    }
}
