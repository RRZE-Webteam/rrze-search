<?php

namespace RRZE\RRZESearch\Application\Shortcode;

class SearchResultsShortcode
{
    public $options;

    public $searchEngine;

    public function __construct()
    {
        $this->options = get_option('rrze_search_settings');
    }

    public function register()
    {
        add_shortcode('rrze_search_results', array($this, 'shortcodeInit'));
    }

    public function shortcodeInit()
    {
        $query      = $_GET['q'];
        $startPage  = $_GET['start'] ?? '1';
        $resources  = $this->options['rrze_search_resources'];
        $resource   = $this->options['rrze_search_resources'][$_GET['se']];
        $pageLink   = get_permalink($this->options['rrze_search_page_id']);
        $facadesDir = DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR;

        /** Define the Search Engine Resource */
        $this->searchEngine = new $resource['resource_class'];

        /** Define Search Engine Class Name */
        $searchEngineClass = substr(strrchr($resource['resource_class'], '\\'), 1);

        /**
         * Finalize Results
         */
        $query_results = $this->searchEngine->Query($query, $resource['resource_key'], $startPage);

        if (gettype($query_results) === 'array') {
            $results = $query_results;
        } else {
            $results = json_decode($query_results, true);
        }

        /** Render the Search Engine Tabs */
        include \dirname(__DIR__, 2).$facadesDir.'search-tabs.php';

        /** Render the Search Engine Results */
        include \dirname(__DIR__,
                2).$facadesDir.'Results'.DIRECTORY_SEPARATOR.$searchEngineClass.'-shortcode.php';
    }
}
