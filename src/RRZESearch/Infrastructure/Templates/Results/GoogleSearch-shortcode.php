<h2><?php _e('Search results','rrze-search'); ?></h2>
<p class="meta-resultinfo"><?php echo sprintf(__('About %1$s results', 'rrze-search'),
        $results['searchInformation']['formattedTotalResults']); ?>
    (<?php echo $results['searchInformation']['formattedSearchTime']; ?> seconds)
</p>
<ul class="searchresults">
    <?php foreach ($results['items'] as $result) : ?>
        <li class="search-result res-page">
            <h3><a href="<?php echo $result['link']; ?>"><?php echo htmlspecialchars($result['title']); ?></a></h3>
            <div class="search-meta"><a href="<?php echo $result['link']; ?>"><cite><?php echo htmlspecialchars($result['link']); ?></cite></a></div>
            <p><?php echo htmlspecialchars($result['snippet']); ?></p>
        </li>
    <?php
    endforeach;
    ?>
</ul>