<div class="results-tabs">
    <?php
    $resourceIndex = 0;
    foreach ($resources as $engine) {
        $query = http_build_query(array(
            'q'  => $_GET['q'],
            'se' => $resourceIndex
        ));
        echo '<a href="?'.$query.'">'.str_replace('(%s)', '',$engine['resource_name']).'</a>';
        $resourceIndex++;
    }
    ?>
</div>