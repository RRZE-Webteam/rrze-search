<?php
$nextEngineIndex = 0;
foreach ($option_value['rrze_search_engines'] as $resourceEngine) {
    $isEnabled = isset($resourceEngine['enabled']) ? 'checked ' : '';
    ?>
    <fieldset>
        <legend class="screen-reader-text">
            <span><?php echo __('Check the box to enabled the '.$resourceEngine['resource_name'].' Search Engine',
                    'rrze-search'); ?></span>
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
            <input type="checkbox" id="<?= $name; ?>"
                   name="<?= $option_name ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][enabled]" <?= $isEnabled; ?>>
            <?= $resourceEngine['resource_name']; ?>
        </label>
    </fieldset>
    <?php
    $nextEngineIndex++;
}
?>