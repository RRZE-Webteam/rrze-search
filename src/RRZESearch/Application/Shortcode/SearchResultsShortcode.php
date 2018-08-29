<?php

namespace RRZE\RRZESearch\Application\Shortcode;

class SearchResultsShortcode
{
    public $options;

//    public $searchEngine;

    public function __construct()
    {
        /**
         * TODO: Define properties outside of constructor
         * TODO: Populate properties values inside constructor
         */
        $this->options = get_option('rrze_search_settings');
//        $this->searchEngine = new GoogleSearch();
    }

    public function register()
    {
        add_shortcode('rrze_search_results', array($this, 'shortcodeInit'));
    }

    public function shortcodeInit()
    {
        $output    = '';
        $query     = $_GET['q'];
        $startPage = isset($_GET['start']) ? $_GET['start'] : '1';
        $resource  = $this->options['rrze_search_resources'][$_GET['se']];
        $pageLink  = get_permalink($this->options['rrze_search_page_id']);

        /**
         * Define the Search Engine Resource;
         */
        $this->searchEngine = new $resource['resource_uri'];

        /**
         * USE JSON file only for dev
         */
//        $query_results = file_get_contents(plugins_url('rrze-search').DIRECTORY_SEPARATOR.'fixture'.DIRECTORY_SEPARATOR.'google_results.json');
        $query_results = $this->searchEngine->Query($query, $resource['resource_key'], $startPage);
        $results       = json_decode($query_results, true);

        $output .= '<div id="resultStats">About '.$results['searchInformation']['formattedTotalResults'].' results<nobr> ('.$results['searchInformation']['formattedSearchTime'].' seconds)&nbsp;</nobr></div>';
        foreach ($results['items'] as $result) {
            $output .= '<div class="record">';
            $output .= '<h3 style="padding-bottom:0">';
            $output .= '<a href="'.$result['link'].'">'.$result['title'].'</a>';
            $output .= '</h3>';
            $output .= '<div class="snippet">';
            $output .= '<cite>'.$result['link'].'</cite><br>';
            $output .= '<div class="snippet-string">'.$result['snippet'].'</div>';
            $output .= '</div>';
            $output .= '</div>';
        }
        $output .= '<br><br><br><br>&nbsp;';

        $output .= '<div id="">';
        if (isset($results['queries']['previousPage'])) {
            $output .= '<a href="'.site_url().$pageLink.'?q='.rawurlencode($query).'&se='.$_GET['se'].'&start='.$results['queries']['previousPage'][0]['startIndex'].'">Previous Page</a>';
        }
        if (isset($results['queries']['previousPage'], $results['queries']['nextPage'])) {
            $output .= '&nbsp;|&nbsp;';
        }
        if (isset($results['queries']['nextPage'])) {
            $output .= '<a href="'.site_url().$pageLink.'?q='.rawurlencode($query).'&se='.$_GET['se'].'&start='.$results['queries']['nextPage'][0]['startIndex'].'">Next Page</a>';
        }
        $output .= '</div>';

        return $output;
    }
}