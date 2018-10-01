<div id="resultStats"><?php echo sprintf(__('About %1$s results', 'rrze-search'),
        \count($results)); ?>
</div>
<?php foreach ($results as $result) { ?>
    <div class="record">
        <h3 style="padding-bottom:0;">
            <a href="<?php echo get_permalink($result['ID']); ?>"><?php echo $result['post_title']; ?></a>
        </h3>
        <div class="snippet">
            <cite><?php echo $_SERVER['HTTP_HOST'].'/'.$result['post_name']; ?></cite>
            <div class="snippet-string"><?php echo $result['post_excerpt']; ?></div>
        </div>
    </div>
<?php } ?>
<hr>

<div id="">
    <?php
    if (isset($results['queries']['previousPage'])) {
        echo '<a href="'.$pageLink.'?q='.urlencode($_GET['q']).'&se='.$_GET['se'].'&start='.$results['queries']['previousPage'][0]['startIndex'].'">'.__('Previous Page', 'rrze-search').'</a>';
    }
    if (isset($results['queries']['previousPage'], $results['queries']['nextPage'])) {
        echo '&nbsp;|&nbsp;';
    }
    if (isset($results['queries']['nextPage'])) {
        echo '<a href="'.$pageLink.'?q='.urlencode($_GET['q']).'&se='.$_GET['se'].'&start='.$results['queries']['nextPage'][0]['startIndex'].'">'.__('Next Page', 'rrze-search').'</a>';
    } ?>
</div>