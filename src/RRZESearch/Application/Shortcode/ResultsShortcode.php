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
	$pageLink     = get_permalink($this->options['rrze_search_page_id']);
	$templatesDir = DIRECTORY_SEPARATOR.'Infrastructure'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR;
	
	$query = '';
	if (isset($_GET['q'])) {
	    $query = esc_attr($_GET['q']);
	} elseif (isset($_GET['s'])) {
	    $query = esc_attr($_GET['s']);
	}
	$useengine = 0;
	if (isset($_GET['se'])) {
	    $useengine  = intval($_GET['se']);
	}
		
	if ((isset($useengine)) && (isset($this->options['rrze_search_resources'][$useengine]))) {
	    $resource     = $this->options['rrze_search_resources'][$useengine];
	}
			
	if (empty($query)) {
	    // Render the Search Engine Results
	    include \dirname(__DIR__, 2).$templatesDir.'Results'.DIRECTORY_SEPARATOR.'Error-NoQuery.php';

	} elseif (isset($resource)) {
	    $startPage    = 1;    
	    if ((isset($_GET['start'])) && (absint($_GET['start']) > 0)) {
	        $startPage = absint($_GET['start']);
	    }
	    
	     // Render the Search Engine Tabs
	    include \dirname(__DIR__, 2).$templatesDir.'search-tabs.php';

	    // Define the Search Engine Resource & class name
	    $this->searchEngine = new $resource['resource_class'];
	    $searchEngineClass  = substr(strrchr(get_parent_class($this->searchEngine), '\\'), 1);
	    // Finalize Results
	    $queryResults = $this->searchEngine->query($query, $resource['args'], $startPage);
	    $results      = is_array($queryResults) ? $queryResults : json_decode($queryResults, true);
	   
	    if ((isset($results['error'])) && ($results['error']['code']>=400)) {
		// Search was denied / was not possible by search provider
		
		echo "<!-- ";
		var_dump($results);
		echo " -->";
		 
		include \dirname(__DIR__, 2).$templatesDir.'Results'.DIRECTORY_SEPARATOR.'Error-shortcode.php';
		// Workaround with a message.  Later enter a fallback to local search here
		
		
	    }  else {
		 // Render the Search Engine Results
		include \dirname(__DIR__, 2).$templatesDir.'Results'.DIRECTORY_SEPARATOR.$searchEngineClass.'-shortcode.php';

		// Render the Pagination
		include \dirname(__DIR__, 2).$templatesDir.'search-pagination.php';
	    }


	} else {
	     // Render the Search Engine Tabs
	    include \dirname(__DIR__, 2).$templatesDir.'search-tabs.php';

	    // Render the Search Engine Results
	    include \dirname(__DIR__, 2).$templatesDir.'Results'.DIRECTORY_SEPARATOR.'Error-shortcode.php';


	   
	}
	
    }
}
