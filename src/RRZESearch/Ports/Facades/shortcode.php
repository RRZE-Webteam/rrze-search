<div id="resultStats">About <?php echo $results['searchInformation']['formattedTotalResults']; ?> results
    <nobr> (<?php echo $results['searchInformation']['formattedSearchTime']; ?> seconds)&nbsp;</nobr>
</div>
<?php
foreach ($results['items'] as $result) {
    ?>
    <div class="record">
        <h3 style="padding-bottom:0">
            <a href="<?php echo $result['link']; ?>"><?php echo $result['title']; ?></a>
        </h3>
        <div class="snippet">
            <cite><?php echo $result['link']; ?></cite><br>
            <div class="snippet-string"><?php echo $result['snippet']; ?></div>
        </div>
    </div>
    <?php
} ?>
<hr>

<div id="">
    <?php
    if (isset($results['queries']['previousPage'])) {
        echo '<a href="'.site_url().$pageLink.'?q='.rawurlencode($query).'&se='.$_GET['se'].'&start='.$results['queries']['previousPage'][0]['startIndex'].'">Previous Page</a>';
    }
    if (isset($results['queries']['previousPage'], $results['queries']['nextPage'])) {
        echo '&nbsp;|&nbsp;';
    }
    if (isset($results['queries']['nextPage'])) {
        echo '<a href="'.site_url().$pageLink.'?q='.rawurlencode($query).'&se='.$_GET['se'].'&start='.$results['queries']['nextPage'][0]['startIndex'].'">Next Page</a>';
    } ?>
</div>