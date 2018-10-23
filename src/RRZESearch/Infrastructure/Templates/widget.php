<?php

/**
 * data
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage ${NAMESPACE}
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2018 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2018 tollwerk GmbH <info@tollwerk.de>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

/**
 * Widget include template
 *
 * @param array $args            Widget parameters
 * @param array $instance        Instance parameters
 * @param array $preferredEngine Preferred search engine
 */

global $staticLinks;
global $privacyLabel;

?>
    <dialog id="search-header" class="search-header searchform" aria-labelledby="search-title" open><?php
        if (!empty($instance['title'])) {
            echo $args['before_title'].apply_filters('widget_title', $instance['title']).$args['after_title'];
        }
        ?>
        <form method="post" action="<?= admin_url('admin-post.php'); ?>" role="search">
            <input type="hidden" name="action" value="widget_form_submit">
            <header>
                <h2 id="search-title" class="screen-reader-text"><?php echo get_theme_mod('title_hero_search'); ?></h2>
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
		
		
		// Unübersichtliche Geekcodesyntax liefert eine Warning, weil auch nach isset von 's' gefragt werden müsste. 
		// $queryValue = (isset($_GET['q']) ? $_GET['q'] : $_GET['s']); 
		
		
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
                <div class="search-settings" role="radiogroup"
                     aria-label="<?php echo __('Available search engines', 'rrze-search'); ?>"
                     aria-labelledby="search-engines">
                    <p id="search-engines"
                       class="screen-reader-text"><?php echo __('Please select one of the available search engines:',
                            'rrze-search'); ?></p>
                    <?php
                    $nextTabIndex = 0;
                    foreach ($resources as $key => $resource):
                        if ((isset($resource['enabled'])) && ($resource['enabled'])) {
                            ++$nextTabIndex;
                            $linkLabel              = trim($resource['link_label']);
                            $searchEngineActive     = (($preferredEngine == $key) ? '1' : '-1');
                            $searchEngineAttributes = 'tabindex="'.$searchEngineActive.'"';
                            $searchEngineAttributes .= ' aria-checked="'.(($preferredEngine == $key) ? 'true' : 'false').'"';
			    $searchEngineDisclaimer = '';
			    
			    if ((isset($resource['resource_disclaimer'])) && (intval($resource['resource_disclaimer'])>0) ) {
				$searchEngineDisclaimer = ' (<a href="'.get_permalink($resource['resource_disclaimer']).'"';
				if (!empty($privacylabeltarget)) {
				    $searchEngineDisclaimer .= ' target="'.$privacylabeltarget.'"';
				}
				$searchEngineDisclaimer .= ' tabindex="'.$searchEngineActive.'">'.$privacyLabel.'</a>)';
			    }
                            ?>
                            <label>
                                <span>
                                    <input type="radio" name="resource_id" <?= $searchEngineAttributes; ?>
                                           class="search-engine"
                                           value="<?= $key; ?>" <?= checked($preferredEngine, $key, false); ?>>
                                </span>
                                <span>
                                    <?php    
				    echo $resource['resource_name'];
				    if (!empty($searchEngineDisclaimer)) {
					echo $searchEngineDisclaimer;
				    } ?>
				</span>
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

