<div class="wrap">
    <form method="post" action="options.php">
        <?php
        settings_fields('rrze_search_settings');
        do_settings_sections('rrze_search_su');
        submit_button(); ?>
    </form>
</div>