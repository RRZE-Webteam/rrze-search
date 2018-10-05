<nav class="navigation paging-navigation" role="navigation">
    <div class="nav-links">
        <?php
        if (isset($results['queries']['previousPage'])) {
            echo '<a href="'.$pageLink.'?q='.urlencode($_GET['q']).'&se='.$_GET['se'].'&start='.$results['queries']['previousPage'][0]['startIndex'].'">';
            echo '<span class="meta-nav">←</span>';
            echo __('Previous Page', 'rrze-search');
            echo '</a>';
        }
        if (isset($results['queries']['nextPage'])) {
            echo '<a href="'.$pageLink.'?q='.urlencode($_GET['q']).'&se='.$_GET['se'].'&start='.$results['queries']['nextPage'][0]['startIndex'].'" class="next page-numbers">';
            echo __('Next Page', 'rrze-search');
            echo '<span class="meta-nav">→</span>';
            echo '</a>';
        } ?>
    </div>
</nav>