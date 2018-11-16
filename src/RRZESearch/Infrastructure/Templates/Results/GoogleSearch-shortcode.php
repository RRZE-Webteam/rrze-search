<?php

    $withthumb = get_theme_mod('search_display_post_thumbnails');
    $thumb = '';
    global $SnippletAllowedtags;
   
?>

<div class="search-results gcse">
        
<h2><?php _e('Search results','rrze-search'); ?></h2>
<p class="meta-resultinfo"><?php echo sprintf(__('About %1$s results', 'rrze-search'),
        $results['searchInformation']['formattedTotalResults']); ?>
    (<?php echo $results['searchInformation']['formattedSearchTime']; echo ' '.__('seconds', 'rrze-search'); ?>)
    
</p>
<ul class="searchresults">
    <?php foreach ($results['items'] as $result) : 
	$thumb = '';
	?>

        <li class="search-result res-post">
	    <h3><a href="<?php echo $result['link']; ?>"><?php echo htmlspecialchars($result['title']); ?></a></h3>
            <div class="search-meta">
		<?php 
		$linktype = 'post-meta-defaulttype';
		
		
		if ((isset($result['pagemap'])) && (isset($result['pagemap']['person']))) {
		    $linktype = 'post-meta-kontakt';
		} elseif (isset($result['mime'])) {
		    if (strpos($result['mime'],'application') !==false) {
			$linktype = 'post-meta-attachment';
		    }
		}
		?>
		<span class="<?php echo $linktype;?> displaylink"> <em><?php echo htmlspecialchars($result['link']); ?></em></span>
	    </div> 
	    <?php
	    if ((isset($result['pagemap'])) && (isset($result['pagemap']['cse_image'])) ) {
		$thumb = esc_url($result['pagemap']['cse_image'][0]['src'],['https']);
		// only allow https-URLs
	    }
	    if (($withthumb==true) && (!empty($thumb)))  {
		echo '<div class="row">'."\n";  
		echo "\t\t".'<div class="searchresult-image" role="presentation">'."\n"; 

		echo '<img src="'.esc_url($thumb).'"  alt="">';
		echo "\t\t".'</div>'."\n"; 
		echo "\t\t".'<div class="searchresult-imagetext">'."\n"; 

	    }

	    echo '<p class="snippet">'.wp_kses($result['htmlSnippet'],$SnippletAllowedtags).'</p>';

	    if (($withthumb==true) && (!empty($thumb)))  {
		echo "\t</div>\n";
	    }
	    ?>
        </li>
    <?php
    endforeach;
    ?>
</ul>

<div class="gcsc-branding" lang="en"><span class="gcsc-branding-text">powered by</span> <a href="http://cse.google.com/cse/?hl=de" target="_blank" class="gcsc-branding-clickable"><img src="https://www.google.com/cse/static/images/1x/googlelogo_grey_46x15dp.png" class="gcsc-branding-img-noclear" alt="Google" srcset="https://www.google.com/cse/static/images/2x/googlelogo_grey_46x15dp.png 2x"></a><span class="gcsc-branding-text gcsc-branding-text-name">CSE</span></div>

</div>

	