<?php
/**
 * Shared search form used on results pages.
 * Expects the following variables in scope:
 * - $currentQuery (string)
 * - $preferredEngine (string|int)
 * - $availableEngines (array)
 */

$availableEngines = isset($availableEngines) ? (array)$availableEngines : [];
$preferredEngine = isset($preferredEngine) ? (string)$preferredEngine : '';
$currentQuery    = isset($currentQuery) ? $currentQuery : '';

?>
<div class="fau-global-search__outer-wrapper">
    <div class="fau-global-search-wrapper fau-global-search-wrapper--content-size wp-block-fau-elemental-fau-global-search">
        <form itemprop="potentialAction"
              itemscope
              id="fau-global-search-2"
              itemtype="https://schema.org/SearchAction"
              method="post"
              action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
              role="search">

            <meta itemprop="target" content="<?php echo esc_url(home_url('/')); ?>?s={s}">

            <div class="fau-global-search__input-wrapper">
                <input type="hidden" name="action" value="widget_form_submit">

                <input type="search" class="fau-global-search__input" name="s" placeholder="Suchen…"
                       value="<?php echo esc_attr($currentQuery); ?>" autocomplete="off" id="fau-global-search-2-input">

                <button type="submit" class="fau-global-search__button" id="searchsubmit" enterkeyhint="search">
                    <span class="fau-global-search__button-text"><?php esc_html_e('Suchen', 'fau'); ?></span>
                    <span class="fau-global-search__button-icon" aria-hidden="true"></span>
                </button>
            </div>

            <?php if (!empty($availableEngines)) : ?>
                <div class="search-settings" role="radiogroup" aria-labelledby="results-search-engines">
                    <p id="results-search-engines" class="screen-reader-text">
                        <?php echo esc_html__('Please select one of the available search engines:', 'rrze-search'); ?>
                    </p>
                    <?php foreach ($availableEngines as $key => $engine) :
                        if (empty($engine['resource_class'])) {
                            continue;
                        }
                        $engineEnabled = array_key_exists('enabled', $engine) ? (bool)$engine['enabled'] : true;
                        if (!$engineEnabled) {
                            continue;
                        }
                        $resourceName = $engine['resource_name'] ?? (string)$key;
                        $searchEngineActive = ($preferredEngine === (string)$key) ? '1' : '-1';
                        $searchEngineAttributes  = 'tabindex="'.$searchEngineActive.'"';
                        $searchEngineAttributes .= ' aria-checked="'.(($preferredEngine === (string)$key) ? 'true' : 'false').'"';
                        ?>
                        <label>
                            <span>
                                <input type="radio"
                                       class="search-engine"
                                       name="resource_id"
                                       value="<?php echo esc_attr($key); ?>"
                                       <?php echo $searchEngineAttributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                       <?php checked($preferredEngine, (string)$key); ?>>
                            </span>
                            <span><?php echo esc_html($resourceName); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>
