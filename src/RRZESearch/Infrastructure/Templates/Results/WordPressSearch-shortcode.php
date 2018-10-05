<?php
?>

<h2>Suchergebnisse</h2>
<p class="notice-hinweis"><?= __('Es wird nur in diesem Webauftritt gesucht. Um Dokumente und Seiten aus anderen Webauftritten zu finden, nutzen Sie die jeweils dort zu findende Suchmaschine oder verwenden eine Internet-Suchmaschine.', 'fau'); ?></p>

<div id="resultStats"><?php echo sprintf(__('About %1$s results', 'rrze-search'),
        \count($results)); ?>
</div>

<?php foreach ($results as $result) { ?>
    <?= fau_display_news_teaser($result['ID'], true, 2, false); ?>
<?php } ?>
<hr>
