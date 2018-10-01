<table id="rrze_search_resource_form" class="form-table" border="0">
    <tbody>
        <?php
        $nextEngineIndex = 0;
        foreach ($engines as $resourceEngine) {
            $rowColor  = ($nextEngineIndex % 2) ? '#ddd' : '#bbb';
            $isEnabled = isset($resourceEngine['enabled']) ? 'checked ' : '';
            ?>
            <tr bgcolor="<?php echo $rowColor; ?>">
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
            <span><?php echo sprintf(__('Check the box to enabled the %s Search Engine', 'rrze-search'),
                    $resourceEngine['resource_name']); ?></span>
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
                            <?= sprintf($resourceEngine['resource_name'], ''); ?>
                        </label>
                    </fieldset>
                </td>
                <td>
                    <fieldset>
                        <label class="resource_table_label">
                            <span><?php echo __('Page / Post ID', 'rrze-search'); ?></span>
                            <input type="number" id="<?= $name; ?>"
                                   name="<?= $option_name ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][resource_disclaimer]"
                                   value="<?= $resourceEngine['resource_disclaimer']; ?>">
                        </label>
                    </fieldset>
                </td>
            </tr>
            <?php
            $nextEngineIndex++;
        }
        ?>
    </tbody>
</table>
