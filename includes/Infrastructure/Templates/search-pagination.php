<nav class="navigation paging-navigation" aria-label="<?php _e('More search results','rrze-search'); ?>">
    <div class="nav-links">
        <?php
        if (isset($results['queries']['previousPage'])):
            $previousLink = $pageLink.'?'.http_build_query([
                    'q'     => $_GET['q'],
                    'se'    => $_GET['se'],
                    'start' => $results['queries']['previousPage'][0]['startIndex'],
                ]);
            ?>
            <a class="prev" href="<?= htmlspecialchars($previousLink); ?>">
                <span class="meta-nav">&larr;</span> <?= htmlspecialchars(__('Previous Page', 'rrze-search')); ?>
            </a>
        <?php
        endif;

        if (isset($results['queries']['nextPage'])) :
            $nextLink = $pageLink.'?'.http_build_query([
                    'q'     => $_GET['q'],
                    'se'    => $_GET['se'],
                    'start' => $results['queries']['nextPage'][0]['startIndex'],
                ]);
            ?>
            <a class="next" href="<?= htmlspecialchars($nextLink); ?>">
                <?= htmlspecialchars(__('Next Page', 'rrze-search')); ?> <span class="meta-nav">&rarr;</span> 
            </a>
        <?php
        endif;
        ?>
    </div>
</nav>