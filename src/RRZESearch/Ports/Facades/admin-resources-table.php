<table id="rrze_search_resource_form" class="form-table" border="0">
    <thead>
        <td><strong>Option Label</strong></td>
        <td><strong>Search Engine</strong></td>
        <td><strong>API Key</strong></td>
        <td><strong>Disclaimer Link</strong></td>
        <td>&nbsp;</td>
    </thead>
    <?php
    $i = 0;
    foreach ($resources as $resource) {
        echo '<tr valign="top">';

        /** Option Label */
        echo '<td><input type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_name]" value="'.$resource['resource_name'].'" /></td>';

        /** Search Engine */
        if ($i === 0) {
            echo '<td>WordPress Search';
            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_uri]" value=""/>';
            echo '</td>';
        } else {
            echo '<td>';
            echo '<select id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_uri]'.'">';
            foreach ($this->engines as $key => $value) {
                if ($key === $resource['resource_uri']) {
                    echo '<option value="'.$key.'" selected>'.$value.'</option>';
                } else {
                    echo '<option value="'.$key.'" >'.$value.'</option>';
                }
            }
            echo '</select>';
            echo '</td>';
        }

        /** API Key */
        if ($i === 0) {
            echo '<td>No API Key Required';
            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_key]" value="" />';
            echo '</td>';
        } else {
            echo '<td><input type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_key]" value="'.$resource['resource_key'].'" /></td>';
        }

        /** Disclaimer Link */
        echo '<td>';
        echo '<select id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_disclaimer]'.'" style="width: 150px !important; min-width: 50px; max-width: 150px;">';
        foreach ($this->pages as $pages) {
            if ($pages->post_type === 'page' && $pages->post_status === 'publish') {
                if ($pages->ID === $resource['resource_disclaimer']) {
                    echo '<option value="'.$pages->ID.'" selected>'.$pages->post_title.'</option>';
                } else {
                    echo '<option value="'.$pages->ID.'" >'.$pages->post_title.'</option>';
                }
            }
        }
        echo '</select>';
        echo '</td>';

        /** Remove Button */
        if ($i === 0) {
            echo '<td><input type="button" class="button button-primary" value="Remove" disabled></td>';
        } else {
            echo '<td><a href="javascript:rrze_resource_removal('.$i.')" class="button button-primary">Remove</a></td>';
        }

        echo '</tr>';
        $i++;
    }
    ?>
    <tfoot>
        <td colspan="5" align="right">
            <input type="hidden" id="rrze_search_resource_count" value="<?php echo $i; ?>">
            <input type="button" id="rrze_search_add_resource_form" class="button button-primary" value="Add Resource">
        </td>
    </tfoot>
</table>