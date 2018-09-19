<?php

echo '<pre>';
//print_r($option_value['rrze_search_resources']);
//echo PHP_EOL.'-----------'.PHP_EOL;
print_r($this->engines);
//echo PHP_EOL.'-----------'.PHP_EOL;
//print_r($engines);
//echo PHP_EOL.'-----------'.PHP_EOL;
echo '</pre>';

$nextEngineIndex = 0;
foreach ($option_value['rrze_search_resources'] as $resource) {
    $enabled = isset($resource['isEnabled']) ? 'checked ' : '';
    ?>
    <fieldset>
        <legend class="screen-reader-text">
            <span><?php echo __('Check the box to enabled the '.$resource['resource_name'].' Search Engine',
                    'rrze-search'); ?></span>
        </legend>
        <label>
            <input type="checkbox" id="<?= $option_name; ?>" name="<?= $option_name?>['<?= $name; ?>']['<?= $nextEngineIndex; ?>']" <?= $enabled; ?> >
            <?= $resource['resource_name']; ?>
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