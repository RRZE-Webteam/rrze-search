<ul class="results-tabs"><?php
    $resourceIndex = 0;

    // Run through all search engines
    foreach ($engines as $key => $engine) {
        $class     = new $engine['resource_class'];
        if ($engine['enabled']) :
            $tabMeta = ((int)$_GET['se'] == $key) ? 'aria-current="page"' : '';
            $query = http_build_query(['q' => $_GET['q'], 'se' => ++$resourceIndex]);
            $href  = $class->getRedirectLink().'?'.$query;

            ?>
            <li><?php if (!empty($_GET['se']) && (intval($_GET['se']) == $resourceIndex)): ?><span
                class="current"><?= htmlspecialchars(sprintf($engine['resource_name'],
            '')); ?></span>
        <?php
        else: ?><a
            href="<?= htmlspecialchars($href); ?>" <?= $tabMeta; ?>><?= htmlspecialchars(sprintf($engine['resource_name'],
                '')); ?></a><?php


        endif; ?></li><?php
        endif;
    }
    ?>
</ul>