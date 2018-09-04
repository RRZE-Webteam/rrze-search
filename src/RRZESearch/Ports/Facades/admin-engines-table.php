<table id="rrze_search_engines_form" class="form-table" border="0">
    <thead>
        <td><strong><?php echo __('Class Name', 'rrze-search'); ?></strong></td>
        <td><strong><?php echo __('Api Key', 'rrze-search'); ?></strong></td>
        <td>&nbsp;</td>
    </thead>
    <?php
    $nextEngineIndex = 0;
    foreach ($engines as $engine){
        echo '<h1>'.$engine.'</h1>';
    }

    /** API Key */
    if ($nextEngineIndex === 0) {
        echo '<td>'.__('No API Key Required', 'rrze-search');
        echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextEngineIndex.'][resource_key]" value="" />';
        echo '</td>';
    } else {
        echo '<td><input type="text" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextEngineIndex.'][resource_key]" value="'.$resource['resource_key'].'" /></td>';
    }
    ?>
</table>
<?php

echo 'really working on it!';

echo '<pre>';
print_r($this->engines);
print_r($option_value[$name]);
echo '</pre>';