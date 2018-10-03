<table id="rrze_search_resource_form" class="form-table" border="0">
    <?php
    $nextEngineIndex = 0;
    foreach ($engines as $resourceEngine) {
        $rowColor  = ($nextEngineIndex % 2) ? '#ddd' : '#bbb';
        $isEnabled = isset($resourceEngine['enabled']) ? 'checked ' : '';
        ?>
        <tr bgcolor="<?php echo $rowColor; ?>">
            <td>
                <label>
                    <input type="hidden" id="<?= $name; ?>"
                           name="<?= $optionName ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][resource_id]"
                           value="<?= $resourceEngine['resource_id']; ?>">
                    <input type="hidden" id="<?= $name; ?>"
                           name="<?= $optionName ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][resource_name]"
                           value="<?= $resourceEngine['resource_name']; ?>">
                    <input type="hidden" id="<?= $name; ?>"
                           name="<?= $optionName ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][resource_class]"
                           value="<?= $resourceEngine['resource_class']; ?>">
                    <input type="checkbox" id="<?= $name; ?>"
                           name="<?= $optionName ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][enabled]" <?= $isEnabled; ?>>
                    <?= sprintf($resourceEngine['resource_name'], ''); ?>
                </label>
            </td>
            <td>
                <label class="resource_table_label">
                    <span><?php echo __('Privacy Policy / Instruction Page ID', 'rrze-search'); ?></span>
                    <input type="number" id="<?= $name; ?>"
                           name="<?= $optionName ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][resource_disclaimer]"
                           value="<?= $resourceEngine['resource_disclaimer']; ?>">
                </label>
            </td>
        </tr>
        <?php
        ++$nextEngineIndex;
    }
    ?>
</table>
