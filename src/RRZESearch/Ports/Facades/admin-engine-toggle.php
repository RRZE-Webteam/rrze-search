<?php
$nextEngineIndex = 0;
foreach ($option_value['rrze_search_engines'] as $resourceEngine) {
    $isEnabled = isset($resourceEngine['enabled']) ? 'checked ' : '';
    ?>
    <fieldset>
        <legend class="screen-reader-text">
            <span><?php echo sprintf(__('Check the box to enabled the %s Search Engine',
                    'rrze-search'), $resourceEngine['resource_name']); ?></span>
        </legend>
        <label>
            <input type="hidden" id="<?= $name; ?>"
                   name="<?= $option_name ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][resource_id]"
                   value="<?= $resourceEngine['resource_id']; ?>">
            <input type="hidden" id="<?= $name; ?>"
                   name="<?= $option_name ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][resource_name]"
                   value="<?= $resourceEngine['resource_name']; ?>">
            <input type="hidden" id="<?= $name; ?>"
                   name="<?= $option_name ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][resource_class]"
                   value="<?= $resourceEngine['resource_class']; ?>">
            <input type="hidden" id="<?= $name; ?>"
                   name="<?= $option_name ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][resource_disclaimer]"
                   value="<?= $resourceEngine['resource_disclaimer']; ?>">
            <input type="checkbox" id="<?= $name; ?>"
                   name="<?= $option_name ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][enabled]" <?= $isEnabled; ?>>
            <?= sprintf($resourceEngine['resource_name'], ''); ?>
        </label>
    </fieldset>

    <fieldset>
        <label class="resource_table_label">
            <span><?php echo __('Disclaimer / Instructions', 'rrze-search'); ?></span>
            <?php
            /** Disclaimer Link */
            echo '<select id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextEngineIndex.'][resource_disclaimer]" class="regular-text">';
            echo '<option value="">'.__('---', 'rrze-search').'</option>';
            foreach ($disclaimerPages as $page) {
                $currentPage = $page->ID;
                if ($currentPage === (int)$option_value[$name][$nextEngineIndex]['resource_disclaimer']) {
                    echo '<option value="'.$page->ID.'" selected>'.$page->post_title.'</option>';
                } else {
                    echo '<option value="'.$page->ID.'" >'.$page->post_title.'</option>';
                }
            }
            echo '</select>';
            ?>
        </label>
    </fieldset>
    <?php
    $nextEngineIndex++;
}
?>