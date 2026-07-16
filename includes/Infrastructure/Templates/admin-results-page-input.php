<?php defined('ABSPATH') || exit; ?>
<input type="hidden"
       id="<?php echo esc_attr($name); ?>"
       name="<?php echo esc_attr($optionName); ?>[<?php echo esc_attr($name); ?>]"
       value="<?php echo esc_attr((string) $resultsPage->ID); ?>">
<input type="text"
       value="<?php echo esc_attr(wp_make_link_relative(get_permalink($resultsPage))); ?>"
       class="regular-text"
       readonly>
<p class="description">
    <a href="<?php echo esc_url(get_edit_post_link($resultsPage->ID, 'raw')); ?>">
        <?php echo esc_html__('Edit search results page', 'rrze-search'); ?>
    </a>
    &middot;
    <a href="<?php echo esc_url(get_permalink($resultsPage)); ?>" target="_blank" rel="noopener noreferrer">
        <?php echo esc_html__('View search results page', 'rrze-search'); ?>
    </a>
</p>
