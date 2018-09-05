<table id="rrze_search_engines_form" class="form-table" border="1">
    <thead>
        <td><strong><?php echo __('Class Name', 'rrze-search'); ?></strong></td>
        <td><strong><?php echo __('API URI', 'rrze-search'); ?></strong></td>
        <td><strong><?php echo __('Enable/Disable', 'rrze-search') ?></strong></td>
    </thead>
    <?php
    $nextEngineIndex = 0;

    foreach ($this->engines as $engine_class => $engine_name) {
        echo '<tr valign="top">';

        if ($nextEngineIndex !== 0) {
            echo '<td>';
            echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextEngineIndex.'][class]" value="'.$engine_class.'" readonly />';
            echo '<input type="text" value="'.$engines[$engine_class]['name'].'" readonly />';
            echo '</td><td>';
            echo '<input type="text" value="'.$engines[$engine_class]['uri'].'" readonly />';
            echo '</td><td>';
            $enabled = (isset($option_value[$name][$nextEngineIndex]['enabled'])) ? 'checked ' : '';
            echo '<input type="checkbox" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextEngineIndex.'][enabled]" '.$enabled.' />';
            echo '</td>';
        }

        echo '</tr>';
        $nextEngineIndex++;
    }


    /** API Key */
    //    if ($nextEngineIndex === 0) {
    //        echo '<td>'.__('No API Key Required', 'rrze-search');
    //        echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextEngineIndex.'][resource_key]" value="" />';
    //        echo '</td>';
    //    } else {
    //        echo '<td><input type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextEngineIndex.'][resource_key]" value="'.$resource['resource_key'].'" /></td>';
    //    }
    ?>
</table>