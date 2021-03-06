<?php
/* 
 * Constant values 
 */


// Links for overlay on clicking into search field

$staticLinks = array( 
	'tab1'	=> array(
	    'html'  => '<img role="presentation" src="'.plugins_url('assets/img', __FILE__).'/fau-logo.svg" width="200" height="54" alt="">',
	),
	'tab2'	=> array(
	    'header' => __('Portale', 'rrze-search' ),
	    'links' => array(
		'link1'  => array(
		    'label'	    => __('Mein Campus', 'rrze-search' ),
		    'href'  => 'https://campus.fau.de/',
		),
		'link2'  => array(
		    'label'	    => __('UnivIS', 'rrze-search' ),
		    'href'  => 'http://univis.fau.de/',
		),
		'link3'  => array(
		    'label'	    => __('Lageplan', 'rrze-search' ),
		    'href'  => 'https://karte.fau.de/',
		),
	    ),
	)
);

$privacylabeltarget = '_blank';

$SnippletAllowedtags = array(
	'em' => array(),
	'b' => array(),
	'strong' => array(),
    );
 