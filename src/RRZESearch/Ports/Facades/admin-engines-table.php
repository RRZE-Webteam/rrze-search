<table>
    <tr>
        <td style="vertical-align: top;"><?php
            echo '<pre>';
            print_r($option_value['rrze_search_resources']);
            echo '</pre>';
            ?>
        </td><td style="vertical-align: top;">
            <?php
            echo '<pre>';
            print_r($option_value['rrze_search_engines']);
            echo '</pre>';
            ?>
        </td>
    </tr>
</table>
<?php
$nextEngineIndex = 0;
foreach ($option_value['rrze_search_engines'] as $resourceEngine) {
    $isEnabled = isset($resourceEngine['enabled']) ? 'checked ' : '';
    ?>
    <fieldset>
        <legend class="screen-reader-text">
            <span><?php echo __('Check the box to enabled the '.$resourceEngine['resource_name'].' Search Engine',
                    'rrze-search'); ?></span>
        </legend>
        <label>
            <input type="hidden" id="<?= $name; ?>"
                   name="<?= $option_name ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][resource_id]"
                   value="<?= $resourceEngine['resource_id']; ?>">
            <input type="hidden" id="<?= $name; ?>"
                   name="<?= $option_name ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][resource_name]"
                   value="<?= $resourceEngine['resource_name']; ?>">
            <input type="hidden" id="<?= $name; ?>"
                   name="<?= $option_name ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][resource_class]"
                   value="<?= $resourceEngine['resource_class']; ?>">
            <input type="checkbox" id="<?= $name; ?>"
                   name="<?= $option_name ?>[<?= $name; ?>][<?= $nextEngineIndex; ?>][enabled]" <?= $isEnabled; ?>>
            <?= $resourceEngine['resource_name']; ?>
        </label>
    </fieldset>
    <?php
    $nextEngineIndex++;
}

//$nextEngineIndex = 0;
//foreach ($this->engines as $engine_class => $engine_name) {
//    if ($nextEngineIndex !== 0) {
//        $enabled = isset($option_value[$name][$nextEngineIndex]['enabled']) ? 'checked ' : '';
//        ?>
<!--        <fieldset>-->
<!--            <legend class="screen-reader-text">-->
<!--                <span>--><?php //echo __('Check the box to enabled the '.$engines[$engine_class]['name'].' Search Engine',
//                        'rrze-search'); ?><!--</span>-->
<!--            </legend>-->
<!--            <label>-->
<!--                --><?php
//                echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextEngineIndex.'][class]" value="'.$engine_class.'" readonly />';
//                echo '<input type="checkbox" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextEngineIndex.'][enabled]" '.$enabled.' />';
//                echo ' '.$engines[$engine_class]['name'];
//                ?>
<!--            </label>-->
<!--        </fieldset>-->
<!--        --><?php
//    }
//    $nextEngineIndex++;
//}
?>