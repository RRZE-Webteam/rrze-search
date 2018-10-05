<div class="results-tabs">
    <?php
    $resourceIndex = 0;
    // Run through all search engines
    foreach ($engines as $key => $engine) {
        $tabMeta = ((int)$_GET['se'] == $key) ? 'aria-current="page"' : '';
        $query   = http_build_query(['q' => $_GET['q'], 'se' => $resourceIndex++]);
        if ($engine['enabled']) {
            echo '<a href="?'.$query.'" '.$tabMeta.'>'.sprintf($engine['resource_name'], '').'</a>';
        }
    }
    ?>
</div>