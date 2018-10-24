<?php
/* 
 * Constant values 
 */


// Links for overlay on clicking into search field

$staticLinks = array( 
	'tab1'	=> array(
	    'html'  => '<img role="presentation" src="/wp-content/uploads/sites/9/2018/10/FAU-Logo-240x65.svg" width="" height="" alt="">',
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
 