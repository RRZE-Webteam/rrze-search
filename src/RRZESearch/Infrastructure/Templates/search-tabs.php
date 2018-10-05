<div class="results-tabs">
    <?php
    $resourceIndex = 0;
    // Run through all search engines
    foreach ($engines as $key => $engine) {
        $tabMeta = ((int)$_GET['se'] == $key) ? 'aria-current="page"' : '';
        $query   = http_build_query(['q' => $_GET['q'], 'se' => $resourceIndex++]);
        $class = new $engine['resource_class'];

        if ($engine['enabled']) {
            echo '<a href="'.$class->getRedirectLink().'?'.$query.'" '.$tabMeta.'>'.sprintf($engine['resource_name'], '').'</a>';
        }
    }
    ?>
</div>