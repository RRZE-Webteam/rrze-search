<input type="hidden" id="<?php echo $name; ?>" name="<?php echo $optionName; ?>[<?php echo $name; ?>]"
       value="<?php echo get_post($optionValue[$name])->ID; ?>">
<input type="text" value="<?php echo wp_make_link_relative(get_permalink($optionValue[$name])); ?>" class="regular-text"
       readonly>