<table id="rrze_search_resource_form" class="form-table" border="0">
    <tbody>
        <?php
        $nextResourceIndex = 0;
        foreach ($resources as $resource) {
            $rowColor = ($nextResourceIndex % 2) ? '#ddd' : '#bbb';
            $uId      = ($resource['resource_id'] !== '') ? $resource['resource_id'] : uniqid('rrze_', true);
            /** Unique Id */
            echo '<input type="hidden" class="regular-text" id="'.$name.'" name="'.$optionName.'['.$name.']['.$nextResourceIndex.'][resource_id]" value="'.$uId.'" />';
            /** Option Label */
            echo '<input type="hidden" class="regular-text" id="'.$name.'" name="'.$optionName.'['.$name.']['.$nextResourceIndex.'][resource_name]" value="'.$resource['resource_name'].'" />';
            ?>
            <tr bgcolor="<?php echo $rowColor; ?>">
                <td style="vertical-align:top">
                    <fieldset>
                        <label class="resource_table_label">
                            <span><?php echo __('Type', 'rrze-search'); ?></span>
                            <?php
                            /** Search Engine Class */
                            echo '<select id="'.$name.'" name="'.$optionName.'['.$name.']['.$nextResourceIndex.'][resource_class]" class="regular-text">';
                            foreach ($this->engines as $key => $value) {
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
                <td>
                    <fieldset>
                        <label class="resource_table_label">
                            <span><?php echo __('API Key', 'rrze-search'); ?></span>
                            <?php
                            /** API Key */
                            if ($resource['resource_key'] === '') {
                                echo '<input class="regular-text" type="text" id="'.$name.'" name="'.$optionName.'['.$name.']['.$nextResourceIndex.'][resource_key]" placeholder="'.__('No API Key',
                                        'rrze-search').'" value="" />';
                            } else {
                                echo '<input class="regular-text" type="text" id="'.$name.'" name="'.$optionName.'['.$name.']['.$nextResourceIndex.'][resource_key]" value="'.$resource['resource_key'].'" />';
                            }
                            ?>
                        </label>
                    </fieldset>
                </td>

                <td>
                    <a href="javascript:rrze_resource_removal(<?php echo $nextResourceIndex; ?>)"
                       class="button button-primary"><?php echo __('Remove', 'rrze-search'); ?></a>
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