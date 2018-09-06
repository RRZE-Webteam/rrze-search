<div id="resultStats"><?php echo sprintf(__('About %1$s results', 'rrze-search'),
        $results['webPages']['totalEstimatedMatches']); ?>
<!--    <nobr> (--><?php //echo $results['searchInformation']['formattedSearchTime']; ?><!-- seconds)&nbsp;</nobr>-->
</div>
<?php
foreach ($results['webPages']['value'] as $result) {
    ?>
    <div class="record">
        <h3 style="padding-bottom:0"><a href="<?php echo $result['url']; ?>"><?php echo $result['name']; ?></a>
        </h3>
        <div class="snippet">
            <cite><?php echo $result['url']; ?></cite><br>
            <div class="snippet-string"><?php echo $result['snippet']; ?></div>
        </div>
    </div>
    <?php
} ?>
<hr>

<div id="">
    <?php
    $resultIndex = (isset($_GET['start'])) ? $_GET['start'] : 0;
    if ($resultIndex > 0) {
        echo '<a href="'.site_url().$pageLink.'?q='.urlencode($_GET['q']).'&se='.$_GET['se'].'&start='.($resultIndex-1).'">'.__('Previous Page',
                'rrze-search').'</a>';
    }
    if ($resultIndex > 0) {
        echo '&nbsp;|&nbsp;';
    }
    if ($resultIndex >= 0 && floor($results['webPages']['totalEstimatedMatches']/10) > $resultIndex) {
        echo '<a href="'.site_url().$pageLink.'?q='.urlencode($_GET['q']).'&se='.$_GET['se'].'&start='.($resultIndex+1).'">'.__('Next Page',
                'rrze-search').'</a>';
    } ?>
</div>