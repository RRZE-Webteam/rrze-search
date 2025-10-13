<?php

include dirname(__DIR__).'/partials/search-form.php';

?>

<h2><?php printf(__('Search results for "%s"', 'rrze-search'), esc_html($currentQuery)); ?></h2>

<div id="resultStats"><?php echo sprintf(__('About %1$s results', 'rrze-search'),
        count($results)); ?>
</div>

<?php foreach ($results as $result) { ?>
    <?= fau_display_news_teaser($result['ID'], true, 2, false); ?>
<?php } ?>
<hr>
