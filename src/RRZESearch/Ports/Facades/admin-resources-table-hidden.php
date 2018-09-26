<table id="rrze_search_resource_form" class="form-table" border="0">
    <tbody>
        <td>
            <?php echo __('Accessible only to SuperAdmins', 'rrze-search')?>
        </td>
        <?php
        $nextResourceIndex = 0;
        foreach ($resources as $resource) {

            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_id]" value="'.$resource['resource_id'].'" />';
            /** Option Label */
            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_name]" value="'.$resource['resource_name'].'" />';

            /** Search Engine Class */
            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_class]" value="'.$resource['resource_class'].'" />';

            /** API Key */
            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_key]" value="'.$resource['resource_key'].'" />';

            /** Disclaimer Link */
            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextResourceIndex.'][resource_disclaimer]" value="'.$resource['resource_disclaimer'].'" />';

            $nextResourceIndex++;
        } ?>
    </tbody>
</table>