<!--<pre>--><?php //print_r($optionValue[$fieldName]); ?><!--</pre>-->
<!--<pre>--><?php //print_r($engines); ?><!--</pre>-->
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
<!--                    <input type="hidden" id="--><?//= $fieldName; ?><!--"-->
<!--                           name="--><?//= $optionName ?><!--[--><?//= $fieldName; ?><!--][--><?//= $nextEngineIndex; ?><!--][resource_id]"-->
<!--                           value="--><?//= $resourceEngine['resource_id']; ?><!--">-->
<!--                    <input type="hidden" id="--><?//= $fieldName; ?><!--"-->
<!--                           name="--><?//= $optionName ?><!--[--><?//= $fieldName; ?><!--][--><?//= $nextEngineIndex; ?><!--][resource_name]"-->
<!--                           value="--><?//= $resourceEngine['resource_name']; ?><!--">-->
<!--                    <input type="hidden" id="--><?//= $fieldName; ?><!--"-->
<!--                           name="--><?//= $optionName ?><!--[--><?//= $fieldName; ?><!--][--><?//= $nextEngineIndex; ?><!--][resource_class]"-->
<!--                           value="--><?//= $resourceEngine['resource_class']; ?><!--">-->
                    <input type="checkbox" id="<?= $fieldName; ?>"
                           name="<?= $optionName ?>[<?= $fieldName; ?>][<?= $nextEngineIndex; ?>][enabled]" <?= $isEnabled; ?>>
                    <?= sprintf($resourceEngine['resource_name'], ''); ?>
                    <?php
                    echo'<pre>';
                    print_r($resourceEngine);
                    echo '</pre>';
                    ?>
                </label>
            </td>
            <td>
                <label class="resource_table_label">
                    <span><?php _e('Privacy Policy / Instruction Page ID', 'rrze-search'); ?></span>
                    <input type="number" id="<?= $fieldName; ?>"
                           name="<?= $optionName ?>[<?= $fieldName; ?>][<?= $nextEngineIndex; ?>][resource_disclaimer]"
                           value="<?= $resourceEngine['resource_disclaimer']; ?>">
                </label>
            </td>
        </tr>
        <?php
        ++$nextEngineIndex;
    }
    ?>
</table>
