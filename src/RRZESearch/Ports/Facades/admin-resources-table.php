<table id="rrze_search_resource_form" class="form-table" border="1">
    <tbody>
        <?php
        $nextResourceIndex = 0;
        foreach ($resources as $resource) {
            $isEnabled = ($nextResourceIndex !== 0) ? $engines[$resource['resource_class']]['enabled'] : 'true';
            $rowColor = ($nextResourceIndex % 2) ? '#bbb' : '#ddd';
            ?>
            <tr bgcolor="<?php echo $rowColor; ?>">
                <td rowspan="3" style="vertical-align:top">
                    <fieldset>
                        <?php
                        /** Option Label */
                        /** Enabled */
                        echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][isEnabled]" value="'.$isEnabled.'" />';
                        /** Option Label */
                        echo '<input type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_name]" value="'.$resource['resource_name'].'" />';
                        ?>
                        <p class="description"><?php echo __('Resource Label', 'rrze-search'); ?></p>
                    </fieldset>
                </td>

                <td><strong><?php echo __('Class Name', 'rrze-search'); ?></strong></td>
                <td><?php
                    /** Search Engine Class */
                    if ($nextResourceIndex === 0) {
                        echo __('WordPress Search', 'rrze-search');
                        echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_class]" value=""/>';
                    } else {
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
                    }
                    ?></td>

                <td rowspan="3">
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
            <tr bgcolor="<?php echo $rowColor; ?>">
                <td><strong><?php echo __('API Key', 'rrze-search'); ?></strong></td>
                <td><?php
                    /** API Key */
                    if ($nextResourceIndex === 0) {
                        echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_key]" value="" />';
                        echo '<input class="regular-text" type="text" value="'.__('No API Key Required',
                                'rrze-search').'" />';
                    } else {
                        echo '<input class="regular-text" type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_key]" value="'.$resource['resource_key'].'" />';
                    }
                    ?></td>
            </tr>
            <tr bgcolor="<?php echo $rowColor; ?>">
                <td><strong><?php echo __('Disclaimer Link', 'rrze-search'); ?></strong></td>
                <td><?php
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
                    ?></td>
            </tr>
            <?php
            $nextResourceIndex++;
        } ?>
    </tbody>
    <tfoot>
        <td align="center">
            <input type="hidden" id="rrze_search_resource_count" value="<?php echo $nextResourceIndex; ?>">
            <input type="button" id="rrze_search_add_resource_form" class="button button-primary"
                   value="<?php echo __('Add Resource', 'rrze-search'); ?>">
        </td>
        <td colspan="3">&nbsp;</td>
    </tfoot>
</table>