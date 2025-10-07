<?php
/**
 * Widget include template
 *
 * @param array $args Widget parameters
 * @param array $instance Instance parameters
 * @param array $preferredEngine Preferred search engine
 */

global

$staticLinks;

?>
    <div class="menu-modal__content" role="navigation" aria-label="Suchen">
        <div class="menu-modal__search-wrapper"><h3 class="menu-modal__search-heading">Alle Seiten und Dokumente
                durchsuchen:</h3>
            <div class="fau-global-search__outer-wrapper">
                <div class="fau-global-search-wrapper fau-global-search-wrapper--content-size wp-block-fau-elemental-fau-global-search">
                    <form itemprop="potentialAction" itemscope id="fau-global-search-2"
                          itemtype="https://schema.org/SearchAction" method="post"
                          action="<?= admin_url('admin-post.php'); ?>" role="search">
                        <div class="fau-global-search__input-wrapper">
                            <input type="hidden" name="action" value="widget_form_submit">
                            <input type="search" class="fau-global-search__input" name="s" placeholder="Suchen…"
                                   value="" autocomplete="off" id="fau-global-search-2-input">
                            <button type="submit" class="fau-global-search__button" id="searchsubmit"
                                    enterkeyhint="search" value="<?php _e('Suchen', 'fau'); ?>"
                                    tabindex="2">
					<span class="fau-global-search__button-text">
						Suchen					</span>
                                <span class="fau-global-search__button-icon" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="search-settings" role="radiogroup"
                             aria-label="<?php echo __('Available search engines', 'rrze-search'); ?>">
                            <p id="search-engines"
                               class="screen-reader-text"><?php echo __('Please select one of the available search engines:', 'rrze-search'); ?></p>
                            <?php
                            $nextTabIndex = 0;
                            foreach ($resources as $key => $resource):
                                if ((isset($resource['enabled'])) && ($resource['enabled'])) {
                                    ++$nextTabIndex;
                                    $searchEngineActive = (($preferredEngine == $key) ? '1' : '-1');
                                    $searchEngineAttributes = 'tabindex="' . $searchEngineActive . '"';
                                    $searchEngineAttributes .= ' aria-checked="' . (($preferredEngine == $key) ? 'true' : 'false') . '"';
                                    $searchEngineDisclaimer = '';

                                    if ((isset($resource['resource_disclaimer'])) && (intval($resource['resource_disclaimer']) > 0)) {
                                        $searchEngineDisclaimer = ' (<a href="' . get_permalink($resource['resource_disclaimer']) . '"';
                                        if (!empty($privacylabeltarget)) {
                                            $searchEngineDisclaimer .= ' target="' . $privacylabeltarget . '"';
                                        }
                                        $searchEngineDisclaimer .= ' tabindex="' . $searchEngineActive . '">' . __('Privacy Disclaimer', 'rrze-search') . '</a>)';
                                    }
                                    ?>
                                    <label>
                                <span>
                                    <input type="radio"
                                           name="resource_id" <?= $searchEngineAttributes; ?> class="search-engine"
                                           value="<?= $key; ?>" <?= checked($preferredEngine, $key, false); ?>>
                                </span>
                                        <span><?php
                                            echo esc_attr($resource['resource_name']);
                                            if (!empty($searchEngineDisclaimer)) {
                                                echo $searchEngineDisclaimer;
                                            }
                                            ?></span>
                                    </label>
                                    <?php
                                }
                            endforeach;
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php