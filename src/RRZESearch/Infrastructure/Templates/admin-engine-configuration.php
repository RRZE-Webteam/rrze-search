<?php settings_errors(); ?>
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
                        <label class="resource_table_label">
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

                    <fieldset>
                        <label class="resource_table_label">
                            <span><?php echo __('Label', 'rrze-search'); ?></span>
                            <?php

                            echo '<input type="text" class="regular-text" id="'.$fieldName.'" name="'.$optionName.'['.$fieldName.']['.$nextResourceIndex.'][resource_name]" value="'.$resource['resource_name'].'" />';
                            ?>
                        </label>
                    </fieldset>
                </td>
                <td style="vertical-align:top">
                    <?php if (!empty($this->enginesClassCollection[$resource['resource_class']]['variables'])) {
                        $engineVariables = $this->enginesClassCollection[$resource['resource_class']]['variables']; ?>
                        <?php foreach ($engineVariables as $index => $engineVariable) { ?>
                            <fieldset>
                                <label class="resource_table_label">
                                    <span><?= strtoupper($engineVariable); ?></span>
                                    <?php
                                    echo '<input type="text" class="regular-text" id="'.$fieldName.'" name="'.$optionName.'['.$fieldName.']['.$nextResourceIndex.'][args]['.$engineVariable.']" value="'.$resource['args'][$engineVariable].'" />';
                                    ?>
                                </label>
                            </fieldset>
                        <?php }
                    } ?>
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