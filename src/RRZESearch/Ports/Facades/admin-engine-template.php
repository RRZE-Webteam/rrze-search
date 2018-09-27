<template>
    <tr style="border: thin solid black; background-color: #F5F5F5">
        <td>
            <fieldset>
                <label class="resource_table_label">
                    <span><?php echo __('Label', 'rrze-search'); ?></span>
                    <input type="hidden" class="regular-text" id="rrze_search_resources"
                           name="rrze_search_settings[rrze_search_resources][index][resource_id]"
                           value="<?php echo uniqid('rrze_', true); ?>">
                    <input type="text" class="regular-text" id="rrze_search_resources"
                           name="rrze_search_settings[rrze_search_resources][index][resource_name]"
                           placeholder="<?php echo __('My Search Engine', 'rrze-search'); ?>" value="">
                </label>
            </fieldset>

            <fieldset>
                <label class="resource_table_label">
                    <span><?php echo __('Disclaimer / Instructions', 'rrze-search'); ?></span>
                    <input type="text" class="regular-text" id="rrze_search_resources"
                           name="rrze_search_settings[rrze_search_resources][index][link_label]"
                           placeholder="<?php echo __('Disclaimer / Instructions', 'rrze-search'); ?>" value="">
                </label>
            </fieldset>
        </td>
        </td>
        <td>
            <fieldset>
                <label class="resource_table_label">
                    <span><?php echo __('Type', 'rrze-search'); ?></span>
                    <select id="rrze_search_resources" class="regular-text"
                            name="rrze_search_settings[rrze_search_resources][index][resource_class]">
                        <?php
                        echo PHP_EOL;
                        foreach ($this->engines as $key => $value) {
                            echo '<option value="'.$key.'" >'.$value.'</option>';
                        } ?></select>
                </label>
            </fieldset>

            <fieldset>
                <label class="resource_table_label">
                    <span><?php echo __('API Key', 'rrze-search'); ?></span>
                    <input type="text" class="regular-text" id="rrze_search_resources"
                           name="rrze_search_settings[rrze_search_resources][index][resource_key]"
                           placeholder="<?php echo __('Leave blank if no API Key', 'rrze-search'); ?>" value="">
                </label>
            </fieldset>
        </td>

        <td>&nbsp;</td>
    </tr>
</template>