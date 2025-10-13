<template>
    <tr style="border: thin solid black; background-color: #F5F5F5">
        <td>
            <input type="hidden" id="rrze_search_resources"
                   name="rrze_search_settings[rrze_search_resources][index][resource_id]"
                   value="uid">
            <input type="hidden" id="rrze_search_resources"
                   name="rrze_search_settings[rrze_search_resources][index][resource_name]"
                   value="">

            <fieldset>
                <label class="resource_table_label">
                    <span><?php echo __('Type', 'rrze-search'); ?></span>
                    <select id="rrze_search_resources" class="regular-text"
                            name="rrze_search_settings[rrze_search_resources][index][resource_class]">
                        <?php
                        echo PHP_EOL;
                        foreach ($this->enginesClassCollection as $key => $value) {
                            echo '<option value="'.$key.'" >'.$value['name'].'</option>';
                        } ?></select>
                </label>
            </fieldset>
        </td>
        <td colspan="2">
            <?= __('Additional Configuration can be made after you Save Changes', 'rrze-seaerch'); ?>
        </td>
    </tr>
</template>