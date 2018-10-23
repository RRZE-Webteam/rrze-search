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

// global $staticLinks;
global $staticLinks;
if (count($staticLinks)):
    ?><h2 class="screen-reader-text"><?php _e('Search suggestions', 'rrze-search'); ?></h2>
    <div class="search-static-links-columns" data-columns="<?= count($staticLinks); ?>"><?php

        foreach ($staticLinks as $searchLinkColumn):
	    if ((isset($searchLinkColumn['links'])) && (is_array($searchLinkColumn['links']))) { ?>
            <div class="search-static-links-column"><?php if (!empty($searchLinkColumn['header'])): ?>
            <h3><?= htmlspecialchars($searchLinkColumn['header']); ?></h3><?php endif;  ?>
                <ul><?php
                foreach ($searchLinkColumn['links'] as $link):
                    if (!empty($link['label'])):
			$linkTarget = $linkClass = '';
		    
			if (isset($link['class'])) {
			    $linkClass = ' class="'.htmlspecialchars($link['class']).'"';
			}                
			if (isset($link['target'])) {
			    $linkTarget = ' target="'.htmlspecialchars($link['target']).'"';
			}
                        
                        ?>
                        <li<?= $linkClass; ?>>
                        <a href="<?= empty($link['href']) ? '#' : htmlspecialchars($link['href']); ?>"
                           tabindex="<?= $nextTabIndex; ?>" <?= $linkTarget; ?>><?= $link['label']; ?></a>
                        </li><?php
                    endif;
                endforeach;
                ?></ul>
            </div><?php
	    }
        endforeach; ?>
    </div>
<?php
endif;