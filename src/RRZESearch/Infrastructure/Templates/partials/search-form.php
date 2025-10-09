<?php
$availableEngines = isset($availableEngines) ? (array) $availableEngines : [];
$preferredEngine  = isset($preferredEngine) ? (string) $preferredEngine : '';
$currentQuery     = isset($currentQuery) ? $currentQuery : '';
?>

<div class="fau-global-search__outer-wrapper">
    <div class="fau-global-search-wrapper fau-global-search-wrapper--content-size wp-block-fau-elemental-fau-global-search">
        <form itemprop="potentialAction"
              itemscope
              id="fau-global-search-2"
              itemtype="https://schema.org/SearchAction"
              method="post"
              action="<?php echo esc_url( admin_url('admin-post.php') ); ?>"
              role="search">

            <meta itemprop="target" content="<?php echo esc_url( home_url('/') ); ?>?s={s}">

            <div class="fau-global-search__input-wrapper">
                <input type="hidden" name="action" value="widget_form_submit">
                <input type="search"
                       class="fau-global-search__input"
                       name="s"
                       placeholder="Suchen…"
                       value="<?php echo esc_attr( $currentQuery ); ?>"
                       autocomplete="off"
                       id="fau-global-search-2-input">
                <button type="submit" class="fau-global-search__button" id="searchsubmit" enterkeyhint="search">
                    <span class="fau-global-search__button-text"><?php esc_html_e('Suchen', 'fau'); ?></span>
                    <span class="fau-global-search__button-icon" aria-hidden="true"></span>
                </button>
            </div>

            <?php if ( ! empty( $availableEngines ) ) : ?>
                <fieldset class="search-settings">
                    <legend id="results-search-engines" class="screen-reader-text">
                        <?php echo esc_html__( 'Please select one of the available search engines:', 'rrze-search' ); ?>
                    </legend>

                    <?php
                    $idx = 0;
                    foreach ( $availableEngines as $key => $engine ) :
                        if ( empty( $engine['resource_class'] ) ) {
                            continue;
                        }
                        $engineEnabled = array_key_exists('enabled', $engine) ? (bool) $engine['enabled'] : true;
                        if ( ! $engineEnabled ) {
                            continue;
                        }

                        $idx++;
                        $value        = (string) $key;
                        $id           = 'engine-' . sanitize_html_class( $value ) . '-' . $idx;
                        $resourceName = $engine['resource_name'] ?? $value;
                        ?>
                        <!-- Input VOR Label für CSS-Selektor input:checked + label -->
                        <input
                                type="radio"
                                class="search-engine"
                                name="resource_id"
                                id="<?php echo esc_attr( $id ); ?>"
                                value="<?php echo esc_attr( $value ); ?>"
                            <?php checked( $preferredEngine, $value ); ?>
                        >
                        <label for="<?php echo esc_attr( $id ); ?>">
                            <?php echo esc_html( $resourceName ); ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>
            <?php endif; ?>

        </form>
    </div>
</div>
