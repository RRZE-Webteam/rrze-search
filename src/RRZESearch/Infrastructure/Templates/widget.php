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
?>
    <dialog id="search-header" class="search-header searchform" aria-labelledby="search-title" open><?php
        if (!empty($instance['title'])) {
            echo $args['before_title'].apply_filters('widget_title', $instance['title']).$args['after_title'];
        }
        ?>
        <form method="post" action="<?= admin_url('admin-post.php'); ?>" role="search">
            <!-- Required hidden form input. Defines the action that should be executed by the submission to admin-post.php -->
            <input type="hidden" name="action" value="widget_form_submit">
            <header>
                <h2 id="search-title" class="screen-reader-text"><?php echo get_theme_mod('title_hero_search'); ?></h2>
                <label for="headsearchinput"><?php _e('Geben Sie hier den Suchbegriff ein, um in diesem Webauftritt zu suchen:',
                        'fau'); ?></label>
                <span class="searchicon"> </span>
                <?php $queryValue = (isset($_GET['q']) ? $_GET['q'] : $_GET['s']); ?>
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
            <!--            <div id="search-panel" class="search-panel" style="background-color: #fff; width:800px; left:-450px; position: absolute" -hidden>-->
            <div id="search-panel" class="search-panel" hidden>
                <div class="search-settings" role="radiogroup"
                     aria-labelledby="<?php echo __('Available search engines',
                         'rrze-search'); ?>">
                    <p id="search-engines"><?php echo __('Please select one of the available search engines:',
                            'rrze-search'); ?></p>
                    <?php
                    $nextTabIndex = 0;
                    foreach ($resources as $key => $resource):
                        if ($resource['enabled']) {
                            ++$nextTabIndex;
                            $linkLabel              = trim($resource['link_label']);
                            $searchEngineActive     = (($preferredEngine == $key) ? '1' : '-1');
                            $searchEngineAttributes = 'tabindex="'.$searchEngineActive.'"';
                            $searchEngineAttributes .= ' aria-checked="'.(($preferredEngine == $key) ? 'true' : 'false').'"';
                            $searchEngineDisclaimer = strlen($resource['resource_disclaimer']) ?
                                ' (<a href="'.get_permalink($resource['resource_disclaimer']).'" target="_blank" tabindex="'.$searchEngineActive.'">'.$linkLabel.'</a>) ' :
                                '';
                            ?>
                            <label>
                                <span>
                                    <input type="radio" name="resource_id" <?= $searchEngineAttributes; ?>
                                           class="search-engine"
                                           value="<?= $key; ?>" <?= checked($preferredEngine, $key, false); ?>>
                                </span>
                                <span>
                                    <?php if (strlen($searchEngineDisclaimer)) {
                                        echo sprintf($resource['resource_name'], $searchEngineDisclaimer);
                                    } else {
                                        echo sprintf($resource['resource_name'], '');


                                    } ?></span>
                            </label>
                            <?php
                        }
                    endforeach;
                    ?>
                </div>
                <div class="search-static-links"><?php include __DIR__.DIRECTORY_SEPARATOR.'widget-static-links.php'; ?></div>
            </div>
        </form>
    </dialog>
<?php

