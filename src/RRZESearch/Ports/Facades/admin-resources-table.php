<!--<p class="description">Working on it!</p>-->
<table id="rrze_search_resource_form" class="form-table" border="1">
    <tbody>
        <?php
        $nextResourceIndex = 0;
        foreach ($resources as $resource) {
            $isEnabled = ($nextResourceIndex !== 0) ? $engines[$resource['resource_class']]['enabled'] : 'true';
            echo '<tr>';

            echo '<td>';
            echo '<strong>'.__('Option Label', 'rrze-search').'</strong><br>';
            echo '</td>';

            echo '<td>';
            /** Option Label */
            echo '<fieldset>';
            /** Enabled */
            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][isEnabled]" value="'.$isEnabled.'" />';
            /** Option Label */
            echo '<input type="text" class="regular-text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_name]" value="'.$resource['resource_name'].'" />';
            echo '</fieldset>';
            echo '</td>';

            echo '</tr><tr>';

            echo '<td>';
            echo '<strong>'.__('Class Name', 'rrze-search').'</strong><br>';
            echo '</td>';

            echo '<td>';
            /** Search Engine Class */
            echo '<fieldset>';
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
            echo '</fieldset>';
            echo '</td>';

            echo '</tr><tr>';

            echo '<td>';
            echo '<strong>'.__('API Key', 'rrze-search').'</strong><br>';
            echo '</td>';

            echo '<td>';
            echo '<fieldset>';
            /** API Key */
            if ($nextResourceIndex === 0) {
                echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_key]" value="" />';
                echo '<input class="regular-text" type="text" value="'.__('No API Key Required', 'rrze-search').'" />';
            } else {
                echo '<input class="regular-text" type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_key]" value="'.$resource['resource_key'].'" />';
            }
            echo '</fieldset>';
            echo '</td>';

            echo '</tr><tr>';

            echo '<td>';
            echo '<strong>'.__('Disclaimer Link', 'rrze-search').'</strong>';
            echo '</td>';

            echo '<td>';
            echo '<fieldset>';
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
            echo '</fieldset>';
            echo '</td>';

            echo '<tr><td></td><td>';
            /** Remove Button */
            if ($nextResourceIndex === 0) {
                echo '<input type="button" class="button button-primary" value="'.__('Remove',
                        'rrze-search').'" disabled>';
            } else {
                echo '<a href="javascript:rrze_resource_removal('.$nextResourceIndex.')" class="button button-primary">'.__('Remove',
                        'rrze-search').'</a>';
            }
            echo '</td></tr>';

            $nextResourceIndex++;
        }
        ?>
    </tbody>
    <tfoot>
        <td colspan="2" align="right">
            <input type="hidden" id="rrze_search_resource_count" value="<?php echo $nextResourceIndex; ?>">
            <input type="button" id="rrze_search_add_resource_form" class="button button-primary"
                   value="<?php echo __('Add Resource', 'rrze-search'); ?>">
        </td>
    </tfoot>
</table>