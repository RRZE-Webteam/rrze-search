<table id="rrze_search_resource_form" class="form-table" border="0">
    <thead>
        <td><strong>Search Engine</strong></td>
        <td><strong>URL</strong></td>
        <td><strong>API Key</strong></td>
        <td>&nbsp;</td>
    </thead>
    <?php
    $i = 0;
    foreach ($resources as $resource) {
        echo '<tr valign="top">';
        echo '<td><input type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_name]" value="'.$resource['resource_name'].'" /></td>';

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

        if ($i === 0) {
            echo '<td>No API Key Required';
            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_key]" value="" />';
            echo '</td>';
        } else {
            echo '<td><input type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$i.'][resource_key]" value="'.$resource['resource_key'].'" /></td>';
        }

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
        <td colspan="4" align="right">
            <input type="hidden" id="rrze_search_resource_count" value="<?php echo $i; ?>">
            <input type="button" id="rrze_search_add_resource_form" class="button button-primary" value="Add Resource">
        </td>
    </tfoot>
</table>