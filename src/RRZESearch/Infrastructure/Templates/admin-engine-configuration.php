<?php settings_errors(); ?>
<pre><?php print_r($resources); ?></pre>
<table id="rrze_search_resource_form" class="form-table" border="0">
    <tbody>
        <?php
        $nextResourceIndex = 0;
        foreach ($resources as $resource) {
            $rowColor = ($nextResourceIndex % 2) ? '#ddd' : '#bbb';
            $uId      = ($resource['resource_id'] !== '') ? $resource['resource_id'] : uniqid('rrze_', true);
            /** Unique Id */
            echo '<input type="hidden" class="regular-text" id="'.$fieldName.'" name="'.$optionName.'['.$fieldName.']['.$nextResourceIndex.'][resource_id]" value="'.$uId.'" />';
            ?>
            <tr bgcolor="<?php echo $rowColor; ?>">
                <td style="vertical-align:top">
                    <fieldset>
                        <label class="resource_table_class">
                            <span><?php echo __('Type', 'rrze-search'); ?></span>
                            <?php
                            /** Search Engine Class */
                            echo '<select id="'.$fieldName.'" name="'.$optionName.'['.$fieldName.']['.$nextResourceIndex.'][resource_class]" class="regular-text">';
                            foreach ($this->enginesClassCollection as $key => $value) {
                                if ($key === $resource['resource_class']) {
                                    echo '<option value="'.$key.'" selected>'.$value['name'].'</option>';
                                } else {
                                    echo '<option value="'.$key.'" >'.$value['name'].'</option>';
                                }
                            }
                            echo '</select>';
                            ?>
                        </label>
                    </fieldset>
                </td>
                <td rowspan="2" style="vertical-align:top">
                    <fieldset>
                        <label class="resource_table_variables">
                            <span><?php echo __('API Key', 'rrze-search'); ?></span>
                            <?php
                            /** API Key */
                            if ($resource['resource_key'] === '') {
                                echo '<input type="text" class="regular-text" id="'.$fieldName.'" name="'.$optionName.'['.$fieldName.']['.$nextResourceIndex.'][resource_key]" placeholder="'.__('No API Key',
                                        'rrze-search').'" value="" />';
                            } else {
                                echo '<input type="text" class="regular-text" id="'.$fieldName.'" name="'.$optionName.'['.$fieldName.']['.$nextResourceIndex.'][resource_key]" value="'.$resource['resource_key'].'" />';
                            }
                            ?>
                        </label>
                    </fieldset>
                </td>
                <td rowspan="2">
                    <a href="javascript:rrze_resource_removal(<?php echo $nextResourceIndex; ?>)"
                       class="button button-primary"><?php echo __('Remove', 'rrze-search'); ?></a>
                </td>
            </tr>
            <tr bgcolor="<?php echo $rowColor; ?>">
                <td style="vertical-align:top">
                    <fieldset>
                        <label for="resource_table_label">
                            <span><?php echo __('Label', 'rrze-search'); ?></span>
                            <?php

                            echo '<input type="text" class="regular-text" id="'.$fieldName.'" name="'.$optionName.'['.$fieldName.']['.$nextResourceIndex.'][resource_name]" value="'.$resource['resource_name'].'" />';
                            ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <?php
            $nextResourceIndex++;
        } ?>
    </tbody>
    <tfoot>
        <td colspan="3">
            <input type="hidden" id="rrze_search_resource_count" value="<?php echo $nextResourceIndex; ?>">
            <input type="button" id="rrze_search_add_resource_form" class="button button-primary"
                   value="<?php echo __('Add Search Engine', 'rrze-search'); ?>">
        </td>
    </tfoot>
</table>