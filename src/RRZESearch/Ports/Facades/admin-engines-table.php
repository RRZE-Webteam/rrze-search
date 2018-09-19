<?php
$nextEngineIndex = 0;

foreach ($this->engines as $engine_class => $engine_name) {
    if ($nextEngineIndex !== 0) {
        $enabled = isset($option_value[$name][$nextEngineIndex]['enabled']) ? 'checked ' : '';
        ?>
        <fieldset>
            <legend class="screen-reader-text">
                <span><?php echo __('Check the box to enabled the '.$engines[$engine_class]['name'].' Search Engine', 'rrze-search'); ?></span>
            </legend>
            <label>
                <?php
                echo '<input type="hidden" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextEngineIndex.'][class]" value="'.$engine_class.'" readonly />';
                echo '<input type="checkbox" id="'.$name.'" name="'.$option_name.'['.$name.']['.$nextEngineIndex.'][enabled]" '.$enabled.' />';
                echo ' '.$engines[$engine_class]['name'];
                ?>
            </label>
        </fieldset>
        <?php
    }
    $nextEngineIndex++;
}
?>
<p class="description"><?php echo __('Check the box of Search Enginges that you would like to be enabled', 'rrze-search'); ?></p>