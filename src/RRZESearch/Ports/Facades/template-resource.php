<template>
    <tr>
        <td><input type="text" id="rrze_search_resources"
                   name="rrze_search_settings[rrze_search_resources][index][resource_name]" value=""></td>
        <td><select id="rrze_search_resources" name="rrze_search_settings[rrze_search_resources][index][resource_class]">
                <?php
                echo PHP_EOL;
                foreach ($this->engines as $key => $value) {
                    if ($key === 'WpSearch') {
                        echo '<option value = "'.$key.'" selected > '.$value.'</option >'.PHP_EOL;
                    } else {
                        echo '<option value = "'.$key.'" > '.$value.'</option >'.PHP_EOL;
                    }
                } ?>
            </select></td>
        <td><input type="text" id="rrze_search_resources"
                   name="rrze_search_settings[rrze_search_resources][index][resource_key]" value=""></td>
        <td>&nbsp;</td>
    </tr>
</template>