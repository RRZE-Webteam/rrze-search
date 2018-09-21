<div id="resultStats"><?php echo sprintf(__('About %1$s results', 'rrze-search'),
        \count($results)); ?>
</div>
<?php foreach ($results as $result) { ?>
    <div class="record">
        <h3 style="padding-bottom:0;">
            <a href="<?php echo $result['post_name']; ?>"><?php echo $result['post_title']; ?></a>
        </h3>
<!--        <div class="search-meta">-->
<!--            <span class="post-meta-defaulttype"> Page</span>-->
<!--            <span class="post-meta-date">--><?php //echo date_format(date_create($result['post_modified']), 'F d, Y'); ?><!-- (Last change)</span>-->
<!--        </div>-->
        <div class="snippet">
            <cite><?php echo $_SERVER['HTTP_HOST'].'/'.$result['post_name']; ?></cite>
            <div class="snippet-string"><?php echo $result['post_excerpt']; ?></div>
        </div>
    </div>
<?php } ?>
