<?php defined( 'ABSPATH' ) || exit; ?>
<ul class="results-tabs">
    <?php
    // Run through all search engines
    foreach ($engines as $key => $engine) {
        $class     = new $engine['resource_class'];
        if ($engine['enabled']) :
            $tabMeta = ((int)$_GET['se'] == $key) ? 'aria-current="page"' : '';
            $isLocalSearch = $class->getRedirectLink() === '/';
            $_q    = $isLocalSearch ? 's' : 'q';
            $targetUrl = $isLocalSearch ? home_url('/') : $pageLink;
            $href  = add_query_arg([$_q => $_GET['q'], 'se' => $key], $targetUrl);
            ?><li><?php
            if (isset($_GET['se']) && (intval($_GET['se']) == $key)): ?><span class="current"><?= htmlspecialchars(sprintf($engine['resource_name'],'')); ?></span>
            <?php else: ?><a href="<?= htmlspecialchars($href); ?>" <?= $tabMeta; ?>><?= htmlspecialchars(sprintf($engine['resource_name'],'')); ?></a><?php
            endif; ?></li><?php
        endif;
    } ?>
</ul>
