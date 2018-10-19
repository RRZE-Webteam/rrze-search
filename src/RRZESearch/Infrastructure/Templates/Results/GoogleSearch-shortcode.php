<?php

    $withthumb = get_theme_mod('search_display_post_thumbnails');
    $thumb = '';
?>

<div class="search-results">
<h2><?php __('Search results','rrze-search'); ?></h2>
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
		<span class="post-meta-defaulttype"> <em><?php echo htmlspecialchars($result['displayLink']); ?></em></span>
	    </div>
	    
	    
		<?php
    		if ((isset($result['pagemap'])) && (isset($result['pagemap']['cse_image']))) {
		    $thumb = $result['pagemap']['cse_image'][0]['src'];
		}
		if (($withthumb==true) && (!empty($thumb)))  {
		    echo '<div class="row">'."\n";  
		    echo "\t\t".'<div class="searchresult-image" role="presentation">'."\n"; 

		    echo '<img src="'.fau_esc_url($thumb).'"  alt="">';
		    echo "\t\t".'</div>'."\n"; 
		    echo "\t\t".'<div class="searchresult-imagetext">'."\n"; 

		}

		echo '<p class="snippet"><em>'.htmlspecialchars($result['snippet']).'</em></p>';
		
		
		if (($withthumb==true) && (!empty($thumb)))  {
		    echo "\t</div>\n";
		}
	    ?>
        </li>
    <?php
    endforeach;
    ?>
</ul>
</div>

	