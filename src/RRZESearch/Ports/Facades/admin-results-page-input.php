<input type="hidden" id="<?php echo $name; ?>" name="<?php echo $option_name; ?>[<?php echo $name; ?>]"
       value="<?php echo get_post($options[$name])->ID; ?>">
<input type="text" value="<?php echo wp_make_link_relative(get_permalink($options[$name])); ?>" class="regular-text"
       readonly>