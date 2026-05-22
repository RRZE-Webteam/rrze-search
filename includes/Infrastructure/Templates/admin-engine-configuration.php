<?php settings_errors(); ?>
<table id="rrze_search_resource_form" class="form-table">
    <tbody>
    <?php
    $nextResourceIndex = 0;
    if($resources === 'empty'){
        $resources = [];
    }
    foreach ($resources as $resource) {
        $rowColor = ($nextResourceIndex % 2) ? '#ddd' : '#bbb';
        $resourceId = isset($resource['resource_id']) ? (string) $resource['resource_id'] : '';
        $resourceClass = isset($resource['resource_class']) ? (string) $resource['resource_class'] : '';
        $resourceName = isset($resource['resource_name']) ? (string) $resource['resource_name'] : '';
        $resourceIdForForm = ($resourceId !== '') ? $resourceId : uniqid('rrze_', true);
        /** Unique Id */
        echo '<input type="hidden" class="regular-text" id="'.esc_attr($fieldName).'" name="'.esc_attr($optionName).'['.esc_attr($fieldName).']['.esc_attr((string) $nextResourceIndex).'][resource_id]" value="'.esc_attr($resourceIdForForm).'" />';
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
                            if ($key === $resourceClass) {
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

                        echo '<input type="text" class="regular-text" id="'.$fieldName.'" name="'.$optionName.'['.$fieldName.']['.$nextResourceIndex.'][resource_name]" value="'.esc_attr($resourceName).'" />';
                        ?>
                    </label>
                </fieldset>
            </td>
            <td style="vertical-align:top">
                <?php if ($resourceClass !== '' && !empty($this->enginesClassCollection[$resourceClass]['variables'])) {
                    $engineVariables = $this->enginesClassCollection[$resourceClass]['variables']; ?>
                    <?php foreach ($engineVariables as $index => $engineVariable) { ?>
                        <fieldset>
                            <label class="resource_table_label">
                                <span><?= strtoupper($engineVariable); ?></span>
                                <?php
                                $preval = '';
                                if ((isset($resource['args'])) && (isset($resource['args'][$engineVariable]))) {
                                    $preval = $resource['args'][$engineVariable];
                                }
                                echo '<input type="text" class="regular-text" id="'.$fieldName.'" name="'.$optionName.'['.$fieldName.']['.$nextResourceIndex.'][args]['.$engineVariable.']" value="'.$preval.'" />';
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
