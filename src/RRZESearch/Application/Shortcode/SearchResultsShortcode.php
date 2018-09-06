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
        $query     = $_GET['q'];
        $startPage = isset($_GET['start']) ? $_GET['start'] : '1';
        $resource  = $this->options['rrze_search_resources'][$_GET['se']];
        $pageLink  = get_permalink($this->options['rrze_search_page_id']);

        /**
         * Define the Search Engine Resource;
         */
        $this->searchEngine = new $resource['resource_class'];
        $_className = explode('\\', $resource['resource_class']);
        $className = $_className[count($_className)-1];

        /**
         * USE JSON file only for dev
         */
//        $query_results = file_get_contents(plugins_url('rrze-search').DIRECTORY_SEPARATOR.'fixtur e'.DIRECTORY_SEPARATOR.'google_results.json');
        $query_results = $this->searchEngine->Query($query, $resource['resource_key'], $startPage);
        $results       = json_decode($query_results, true);


        $partialsPath = DIRECTORY_SEPARATOR.'Ports'.DIRECTORY_SEPARATOR.'Facades'.DIRECTORY_SEPARATOR.'Results'.DIRECTORY_SEPARATOR;
        include \dirname(__DIR__,
                2).$partialsPath.$className.'-shortcode.php';
    }
}