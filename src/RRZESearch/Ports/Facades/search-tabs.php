<div class="results-tabs">
    <?php
    $resourceIndex = 0;

    // Run through all search engines
    foreach ($engines as $engine) {
        $query = http_build_query(['q' => $_GET['q'], 'se' => $resourceIndex++]);
        if ($engine['enabled']) {
            echo '<a href="?'.$query.'">'.sprintf($engine['resource_name'], '').'</a>';
        }
    }
    ?>
</div>