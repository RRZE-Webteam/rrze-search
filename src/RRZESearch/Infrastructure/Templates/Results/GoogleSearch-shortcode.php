<div id="resultStats"><?php echo sprintf(__('About %1$s results', 'rrze-search'),
        $results['searchInformation']['formattedTotalResults']); ?>
    <nobr> (<?php echo $results['searchInformation']['formattedSearchTime']; ?> seconds)&nbsp;</nobr>
</div>
<?php foreach ($results['items'] as $result) {
    ?>
    <div class="record">
        <h3 style="padding-bottom:0">
            <a href="<?php echo $result['link']; ?>" target="_blank"><?php echo $result['title']; ?></a>
        </h3>
        <div class="snippet">
            <cite><?php echo $result['link']; ?></cite><br>
            <div class="snippet-string"><?php echo $result['snippet']; ?></div>
        </div>
    </div>
    <?php
} ?>
<hr>
