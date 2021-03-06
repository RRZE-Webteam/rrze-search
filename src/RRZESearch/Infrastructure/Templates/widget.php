<?php
/**
 * Widget include template
 *
 * @param array $args            Widget parameters
 * @param array $instance        Instance parameters
 * @param array $preferredEngine Preferred search engine
 */

global $staticLinks;

?>
    <dialog id="search-header" class="search-header searchform" open>
        <form method="post" action="<?= admin_url('admin-post.php'); ?>" role="search">
            <input type="hidden" name="action" value="widget_form_submit">
            <header>
                <label for="headsearchinput"><?php _e('Geben Sie hier den Suchbegriff ein, um in diesem Webauftritt zu suchen:',
                        'fau'); ?></label>
                <span class="searchicon"> </span>
                <?php 
		
		$queryValue = '';
		if (isset($_GET['q'])) {
		    $queryValue = esc_attr($_GET['q']);
		} elseif (isset($_GET['s'])) {
		    $queryValue = esc_attr($_GET['s']);
		}
		?>
                <input id="headsearchinput" class="search-terms" type="text" value="<?= $queryValue; ?>"
                       name="s"
                       placeholder="<?php _e('Suchen nach...', 'fau'); ?>" autocomplete="off" tabindex="0">
                <?php
                if (get_theme_mod('search_allowfilter')) {
                    $autosearchTypes = get_theme_mod('search_post_types_checked');
                    $listtypes       = fau_get_searchable_fields();
                    if (is_array($listtypes)) {
                        foreach ($listtypes as $type) {
                            if (in_array($type, $autosearchTypes)) {
                                echo '<input type="hidden" name="post_type[]" value="'.$type.'">'."\n";
                            }
                        }
                    }
                }
                ?>
                <input type="submit" id="searchsubmit" value="<?php _e('Finden', 'fau'); ?>" tabindex="2">
            </header>
	    <div id="search-panel" class="search-panel<?php if (count($staticLinks)<=0) { echo ' no-links';} ?>" hidden>
                <div class="search-settings" role="radiogroup" aria-label="<?php echo __('Available search engines', 'rrze-search'); ?>">
                    <p id="search-engines" class="screen-reader-text"><?php echo __('Please select one of the available search engines:',
                            'rrze-search'); ?></p>
                    <?php
                    $nextTabIndex = 0;
                    foreach ($resources as $key => $resource):
                        if ((isset($resource['enabled'])) && ($resource['enabled'])) {
                            ++$nextTabIndex;
                            $searchEngineActive     = (($preferredEngine == $key) ? '1' : '-1');
                            $searchEngineAttributes = 'tabindex="'.$searchEngineActive.'"';
                            $searchEngineAttributes .= ' aria-checked="'.(($preferredEngine == $key) ? 'true' : 'false').'"';
			    $searchEngineDisclaimer = '';
			    
			    if ((isset($resource['resource_disclaimer'])) && (intval($resource['resource_disclaimer'])>0) ) {
				$searchEngineDisclaimer = ' (<a href="'.get_permalink($resource['resource_disclaimer']).'"';
				if (!empty($privacylabeltarget)) {
				    $searchEngineDisclaimer .= ' target="'.$privacylabeltarget.'"';
				}
				$searchEngineDisclaimer .= ' tabindex="'.$searchEngineActive.'">'.__('Privacy Disclaimer', 'rrze-search').'</a>)';
			    }
                            ?>
                            <label>
                                <span>
                                    <input type="radio" name="resource_id" <?= $searchEngineAttributes; ?> class="search-engine" value="<?= $key; ?>" <?= checked($preferredEngine, $key, false); ?>>
                                </span>
                                <span><?php    
				    echo esc_attr($resource['resource_name']);
				    if (!empty($searchEngineDisclaimer)) { echo $searchEngineDisclaimer; } 
				?></span>
                            </label>
                            <?php
                        }
                    endforeach;
                    ?>
                </div>
		<?php if (count($staticLinks)) { ?>
                <div class="search-static-links"><?php include __DIR__.DIRECTORY_SEPARATOR.'widget-static-links.php'; ?></div>
		<?php } ?>
            </div>
        </form>
    </dialog>
<?php

