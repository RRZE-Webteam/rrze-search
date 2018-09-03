<table id="rrze_search_resource_form" class="form-table" border="0">
    <thead>
        <td><strong><?php echo __('Option Label', 'rrze-search'); ?></strong></td>
        <td><strong><?php echo __('Search Engine', 'rrze-search'); ?></strong></td>
        <td><strong><?php echo __('API Key', 'rrze-search'); ?></strong></td>
        <td><strong><?php echo __('Disclaimer Link', 'rrze-search'); ?></strong></td>
        <td>&nbsp;</td>
    </thead>
    <?php
    $nextResourceIndex = 0;
    foreach ($resources as $resource) {
        echo '<tr valign="top">';

        /** Option Label */
        echo '<td><input type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_name]" value="'.$resource['resource_name'].'" /></td>';

        /** Search Engine */
        if ($nextResourceIndex === 0) {
            echo '<td>'.__('WordPress Search', 'rrze-search');
            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_uri]" value=""/>';
            echo '</td>';
        } else {
            echo '<td>';
            echo '<select id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_uri]'.'">';
            $searchEngineIndex = 0;
            foreach ($this->engines as $key => $value) {
                if ($searchEngineIndex > 0) {
                    if ($key === $resource['resource_uri']) {
                        echo '<option value="'.$key.'" selected>'.$value.'</option>';
                    } else {
                        echo '<option value="'.$key.'" >'.$value.'</option>';
                    }
                }
                $searchEngineIndex++;
            }
            echo '</select>';
            echo '</td>';
        }

        /** API Key */
        if ($nextResourceIndex === 0) {
            echo '<td>'.__('No API Key Required', 'rrze-search');
            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_key]" value="" />';
            echo '</td>';
        } else {
            echo '<td><input type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_key]" value="'.$resource['resource_key'].'" /></td>';
        }

        /** Disclaimer Link */
        echo '<td>';
        echo '<select id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_disclaimer]" style="width: 150px !important; min-width: 50px; max-width: 150px;">';
        foreach ($pages as $page) {
            $currentPage = $page->ID;
            if ($currentPage === (int)$option_value[$name][$nextResourceIndex]['resource_disclaimer']) {
                echo '<option value="'.$page->ID.'" selected>'.$page->post_title.'</option>';
            } else {
                echo '<option value="'.$page->ID.'" >'.$page->post_title.'</option>';
            }
        }
        echo '</select>';
        echo '</td>';

        /** Remove Button */
        if ($nextResourceIndex === 0) {
            echo '<td><input type="button" class="button button-primary" value="'.__('Remove',
                    'rrze-search').'" disabled></td>';
        } else {
            echo '<td><a href="javascript:rrze_resource_removal('.$nextResourceIndex.')" class="button button-primary">'.__('Remove',
                    'rrze-search').'</a></td>';
        }

        echo '</tr>';
        $nextResourceIndex++;
    }
    ?>
    <tfoot>
        <td colspan="5" align="right">
            <input type="hidden" id="rrze_search_resource_count" value="<?php echo $nextResourceIndex; ?>">
            <input type="button" id="rrze_search_add_resource_form" class="button button-primary"
                   value="<?php echo __('Add Resource', 'rrze-search'); ?>">
        </td>
    </tfoot>
</table>