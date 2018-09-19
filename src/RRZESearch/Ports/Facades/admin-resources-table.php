<table id="rrze_search_resource_form" class="form-table" border="0">
    <tbody>
        <?php
        $nextResourceIndex = 0;
        foreach ($resources as $resource) {
            $isEnabled = ($nextResourceIndex !== 0) ? $engines[$resource['resource_class']]['enabled'] : 'true';
            $rowColor  = ($nextResourceIndex % 2) ? '#bbb' : '#ddd';
            ?>
            <tr bgcolor="<?php echo $rowColor; ?>">
                <td style="vertical-align:top">
                    <fieldset>
                        <label>
                            <strong><?php echo __('Resource Label', 'rrze-search'); ?></strong>
                            <?php
                            /** Option Label */
                            /** Enabled */
                            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][isEnabled]" value="'.$isEnabled.'" />';
                            /** Option Label */
                            echo '<input type="text" class="regular-text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_name]" value="'.$resource['resource_name'].'" />';
                            ?>
                        </label>
                    </fieldset>
                </td>

                <td width="100%">
                    <fieldset>
                        <label class="resource_table_label">
                            <span><?php echo __('Class Name', 'rrze-search'); ?></span>
                            <?php
                            /** Search Engine Class */
                            if ($isEnabled === 'false') {
                                echo PHP_EOL;
                                echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_class]" value="'.$resource['resource_class'].'">';
                                echo PHP_EOL;
                                echo '<select disabled>';
                            } else {
                                echo '<select id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_class]" class="regular-text">';
                            }
                            $searchEngineIndex = 0;
                            foreach ($this->engines as $key => $value) {
                                if ($searchEngineIndex > 0) {
                                    if ($key === $resource['resource_class']) {
                                        echo '<option value="'.$key.'" selected>'.$value.'</option>';
                                    } else {
                                        echo '<option value="'.$key.'" >'.$value.'</option>';
                                    }
                                }
                                $searchEngineIndex++;
                            }
                            echo '</select>';
                            ?>
                        </label>
                    </fieldset>


                    <fieldset>
                        <label class="resource_table_label">
                            <strong><?php echo __('API Key', 'rrze-search'); ?></strong>
                            <?php
                            /** API Key */
                            $apiValue = ($resource['resource_key'] === '') ? $resource['resource_key'] : __('No API Key Required',
                                'rrze-search');
                            echo '<input class="regular-text" type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_key]" value="'.$apiValue.'" />';
                            ?>
                        </label>
                    </fieldset>


                    <fieldset>
                        <label class="resource_table_label">
                            <strong><?php echo __('Disclaimer Link', 'rrze-search'); ?></strong>
                            <?php
                            /** Disclaimer Link */
                            echo '<select id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_disclaimer]" class="regular-text">';
                            foreach ($pages as $page) {
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
        <td colspan="3" align="center">
            <input type="hidden" id="rrze_search_resource_count" value="<?php echo $nextResourceIndex; ?>">
            <input type="button" id="rrze_search_add_resource_form" class="button button-primary"
                   value="<?php echo __('Add Resource', 'rrze-search'); ?>">
        </td>
    </tfoot>
</table>