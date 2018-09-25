<table id="rrze_search_resource_form" class="form-table" border="0">
    <tbody>
        <?php
        $nextResourceIndex = 0;
        foreach ($resources as $resource) {
            $rowColor = ($nextResourceIndex % 2) ? '#bbb' : '#ddd';
            ?>
            <tr bgcolor="<?php echo $rowColor; ?>">
                <td style="vertical-align:top">
                    <fieldset>
                        <label class="resource_table_label">
                            <span><?php echo __('Resource Label', 'rrze-search'); ?></span>
                            <?php
                            $uId = ($resource['resource_id'] !== '') ? $resource['resource_id'] : uniqid('rrze_', true);
                            echo '<input type="hidden" class="regular-text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_id]" value="'.$uId.'" />';
                            /** Option Label */
                            echo '<input type="text" class="regular-text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_name]" value="'.$resource['resource_name'].'" />';
                            ?>
                        </label>
                    </fieldset>

                    <fieldset>
                        <label class="resource_table_label">
                            <span><?php echo __('Class Name', 'rrze-search'); ?></span>
                            <?php
                            /** Search Engine Class */
                            echo '<select id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_class]" class="regular-text">';
                            foreach ($this->engines as $key => $value) {
                                if ($key === $resource['resource_class']) {
                                    echo '<option value="'.$key.'" selected>'.$value.'</option>';
                                } else {
                                    echo '<option value="'.$key.'" >'.$value.'</option>';
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
                                echo '<input class="regular-text" type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_key]" placeholder="'.__('No API Key',
                                        'rrze-search').'" value="" />';
                            } else {
                                echo '<input class="regular-text" type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_key]" value="'.$resource['resource_key'].'" />';
                            }
                            ?>
                        </label>
                    </fieldset>

                    <fieldset>
                        <label class="resource_table_label">
                            <span><?php echo __('Disclaimer Link', 'rrze-search'); ?></span>
                            <?php
                            /** Disclaimer Link */
                            echo '<select id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_disclaimer]" class="regular-text">';
                            foreach ($disclaimerPages as $page) {
                                $currentPage = $page->ID;
                                if ($currentPage === (int)$option_value[$name][$nextResourceIndex]['resource_disclaimer']) {
                                    echo '<option value="'.$page->ID.'" selected>'.$page->post_title.'</option>';
                                } else {
                                    echo '<option value="'.$page->ID.'" >'.$page->post_title.'</option>';
                                }
                            }
                            echo '</select>';
                            ?>
                        </label>
                    </fieldset>
                </td>

                <td>
                    <?php
                    /** Remove Button */
                    if ($nextResourceIndex === 0) {
                        echo '<input type="button" class="button button-primary" value="'.__('Remove',
                                'rrze-search').'" disabled>';
                    } else {
                        echo '<a href="javascript:rrze_resource_removal('.$nextResourceIndex.')" class="button button-primary">'.__('Remove',
                                'rrze-search').'</a>';
                    }
                    ?>
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
                   value="<?php echo __('Add Resource', 'rrze-search'); ?>">
        </td>
    </tfoot>
</table>