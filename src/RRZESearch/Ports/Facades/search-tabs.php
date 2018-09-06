<div class="results-tabs">
    <?php
    $resourceIndex = 0;
    foreach ($resources as $engine){
        if ($resourceIndex > 0 && $engine['isEnabled'] === 'true'){
            $query = http_build_query(array(
                'q' => $_GET['q'],
                'se' => $resourceIndex
            ));
            echo '<a href="?'.$query.'">'.$engine['resource_name'].'</a>';
        }
        $resourceIndex++;
    }
    ?>
</div>