<template>
    <tr>
        <td rowspan="3" style="vertical-align:top">
            <input type="text" id="rrze_search_resources"
                   name="rrze_search_settings[rrze_search_resources][index][resource_name]"
                   placeholder="<?php echo __('New Resource Label', 'rrze-search'); ?>" value="">
        </td>
        <td>class name</td>
        <td>
            <select id="rrze_search_resources" class="regular-text"
                    name="rrze_search_settings[rrze_search_resources][index][resource_class]">
                <?php
                echo PHP_EOL;
                foreach ($this->engines as $key => $value) {
                    if ($key !== 'WpSearch') {
                        echo '<option value = "'.$key.'" > '.$value.'</option >'.PHP_EOL;
                    }
                } ?>
            </select>
        </td>
        <td rowspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td>api key</td>
        <td>
            <input type="text" class="regular-text" id="rrze_search_resources"
                   name="rrze_search_settings[rrze_search_resources][index][resource_key]"
                   placeholder="<?php echo __('Leave blank if no API Key', 'rrze-search'); ?>" value="">
        </td>
    </tr>
    <tr>
        <td>disclaimer link</td>
        <td>
            <select id="rrze_search_resources" class="regular-text"
                    name="rrze_search_settings[rrze_search_resources][index][resource_disclaimer]">
                <?php
                // This loop through the $pages collection. Which is a collection of Wordpress Pages which use a custom
                // field value, just like tagging them. Only additional task script does is; It keeps an integer index
                // while the loop iterates. The first value is selected.
                //
                // And thus fixes the bug on issue: #1
                echo PHP_EOL;
                $nextPageIndex = 0;
                foreach ($pages as $page) {
                    if ($nextPageIndex === 0) {
                        echo '<option value="'.$page->ID.'" selected>'.$page->post_title.'</option>';
                    } else {
                        echo '<option value="'.$page->ID.'" >'.$page->post_title.'</option>';
                    }
                    $nextPageIndex++;
                }
                ?>
            </select>
        </td>
    </tr>
</template>