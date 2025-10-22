<?php

namespace RRZE\RRZESearch\Infrastructure;

defined('ABSPATH') || exit;

/**
 * Class FAU_Global_Search_Renderer
 */
final class BlockRender
{
    /** @var string Default GET parameter name */
    private const DEFAULT_PARAM = 's';

    /** @var string Wrapper base class */
    private const BASE_WRAPPER_CLASS = 'fau-global-search-wrapper';

    /** @var string Wrapper modifier prefix */
    private const WIDTH_MODIFIER_PREFIX = 'fau-global-search-wrapper--';

    /**
     * Render the block.
     *
     * @param array $attributes Block attributes from register_block_type.
     * @param string $content Block default content (unused).
     * @param WP_Block|null $block Block instance (optional).
     * @return string HTML
     */
    public static function render(array $attributes = [], $content = '', $block = null): string
    {
        // ---- Read & sanitize attributes ---------------------------------
        $width = isset($attributes['width']) ? esc_attr((string)$attributes['width']) : 'content-size';
        $heading = isset($attributes['heading']) ? wp_kses_post((string)$attributes['heading']) : '';

        $rawTargetUrl = isset($attributes['searchTargetUrl']) ? (string)$attributes['searchTargetUrl'] : '';
        $rawTargetUrlTrimmed = trim($rawTargetUrl);

        $rawParam = isset($attributes['searchGetParameter']) ? (string)$attributes['searchGetParameter'] : '';
        $rawParamTrimmed = trim($rawParam);

        $settings = get_option('rrze_search_settings');

        $actionUrl = self::sanitizeActionUrl($rawTargetUrl);
        if ($rawTargetUrlTrimmed === '') {
            $resultsUrl = self::resolveResultsPageUrl($settings);
            if ($resultsUrl !== null) {
                $actionUrl = $resultsUrl;
            }
        }

        $paramName = self::sanitizeParamName($rawParam);

        $availableEngines = self::collectEnabledEngines($settings);
        $shouldUseWidgetSubmission = (
            $rawTargetUrlTrimmed === '' &&
            $rawParamTrimmed === '' &&
            !empty($availableEngines)
        );
        $shouldShowEngineSelector = (
            $shouldUseWidgetSubmission &&
            $width === 'content-size'
        );

        if ($shouldUseWidgetSubmission) {
            $paramName = self::DEFAULT_PARAM;
        }

        $selectedEngine = $shouldUseWidgetSubmission ? self::determinePreferredEngine($availableEngines) : '';

        $formMethod = $shouldUseWidgetSubmission ? 'post' : 'get';
        $formAction = $shouldUseWidgetSubmission ? admin_url('admin-post.php') : $actionUrl;

        // Wrapper attributes
        $wrapperAttributes = get_block_wrapper_attributes([
            'class' => sprintf(
                '%s %s%s',
                self::BASE_WRAPPER_CLASS,
                self::WIDTH_MODIFIER_PREFIX,
                $width
            ),
        ]);

        // Unique and stable-ish form ID
        $formId = 'fau-global-search-' . wp_unique_id();

        // Sticky value: read from GET using the (sanitized) param name
        $currentValue = '';
        if (isset($_GET[$paramName])) {
            $currentValue = sanitize_text_field(wp_unslash($_GET[$paramName]));
        } elseif ($paramName === self::DEFAULT_PARAM && isset($_GET['q'])) {
            $currentValue = sanitize_text_field(wp_unslash($_GET['q']));
        }

        // ---- Build output ------------------------------------------------
        ob_start();
        ?>
        <div class="fau-global-search__outer-wrapper">
            <div <?php echo $wrapperAttributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>>
                <?php if (!empty($heading) && $width === 'full-grid') : ?>
                    <h3 class="fau-global-search__heading fau-global-search__heading--full-grid">
                        <?php echo esc_html($heading); ?>
                    </h3>
                <?php elseif (!empty($heading) && $width !== 'full-grid') : ?>
                    <h3 class="fau-global-search__heading">
                        <?php echo esc_html($heading); ?>
                    </h3>
                <?php endif; ?>

                <form
                        class="fau-global-search fau-global-search__form"
                        method="<?php echo esc_attr($formMethod); ?>"
                        action="<?php echo esc_url($formAction); ?>"
                        id="<?php echo esc_attr($formId); ?>"
                    <?php if ($shouldShowEngineSelector) : ?>
                        data-advanced-features="true" data-enable-autocomplete="true"
                    <?php endif; ?>
                >
                    <?php if ($shouldUseWidgetSubmission) : ?>
                        <input type="hidden" name="action" value="widget_form_submit">
                        <?php if (!$shouldShowEngineSelector && $selectedEngine !== '') : ?>
                            <input type="hidden" name="resource_id" value="<?php echo esc_attr($selectedEngine); ?>">
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="fau-global-search__input-wrapper<?php echo ($width === 'full-grid') ? ' fau-global-search__input-wrapper--full-grid' : ''; ?>">
                        <input
                                type="search"
                                class="fau-global-search__input"
                                name="<?php echo esc_attr($paramName); ?>"
                                placeholder="<?php echo esc_html__('Search…', 'fau-elemental'); ?>"
                                value="<?php echo esc_attr($currentValue); ?>"
                                autocomplete="off"
                                id="<?php echo esc_attr($formId); ?>-input"
                        />
                        <button
                                type="submit"
                                class="fau-global-search__button"
                        >
                                <span class="fau-global-search__button-text">
                                    <?php echo esc_html__('Search', 'fau-elemental'); ?>
                                </span>
                            <span class="fau-global-search__button-icon" aria-hidden="true"></span>
                        </button>
                    </div>
                    <?php if ($shouldShowEngineSelector) : ?>
                        <fieldset class="search-settings" role="radiogroup" aria-labelledby="<?php echo esc_attr($formId); ?>-legend">
                            <legend id="<?php echo esc_attr($formId); ?>-legend" class="screen-reader-text">
                                <?php echo esc_html__('Please select one of the available search engines:', 'rrze-search'); ?>
                            </legend>
                            <?php foreach ($availableEngines as $index => $engineData) :
                                $radioId = sprintf('%s-engine-%d', $formId, $index + 1);
                                $engineId = isset($engineData['id']) ? (string)$engineData['id'] : '';
                                if ($engineId === '') {
                                    continue;
                                }
                                $isChecked = ($engineId === $selectedEngine);
                                ?>
                                <input
                                        type="radio"
                                        class="search-engine"
                                        name="resource_id"
                                        id="<?php echo esc_attr($radioId); ?>"
                                        value="<?php echo esc_attr($engineId); ?>"
                                    <?php checked($isChecked); ?>
                                >
                                <label for="<?php echo esc_attr($radioId); ?>">
                                    <?php echo esc_html($engineData['label']); ?>
                                    <?php if (!empty($engineData['disclaimer_url'])) : ?>
                                        <span class="search-engine__disclaimer">
                                            (<a href="<?php echo esc_url($engineData['disclaimer_url']); ?>">
                                                <?php echo esc_html__('Privacy Disclaimer', 'rrze-search'); ?>
                                            </a>)
                                        </span>
                                    <?php endif; ?>
                                </label>
                            <?php endforeach; ?>
                        </fieldset>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <?php
        return (string)ob_get_clean();
    }

    /**
     * Build the list of enabled search engines with presentation data.
     *
     * @param mixed $settings RRZE Search option array if already loaded.
     * @return array<int, array<string, string>>
     */
    private static function collectEnabledEngines($settings): array
    {
        if (!is_array($settings)) {
            $settings = get_option('rrze_search_settings');
        }

        if (!is_array($settings)) {
            return [];
        }

        $engines = isset($settings['rrze_search_engines']) && is_array($settings['rrze_search_engines'])
            ? $settings['rrze_search_engines']
            : [];

        $collection = [];
        foreach ($engines as $index => $engine) {
            if (!is_array($engine)) {
                continue;
            }

            $resourceClass = isset($engine['resource_class']) ? (string)$engine['resource_class'] : '';
            if ($resourceClass === '') {
                continue;
            }

            $engineEnabled = !isset($engine['enabled']) || (bool)$engine['enabled'];
            if (!$engineEnabled) {
                continue;
            }

            $label = isset($engine['resource_name']) ? trim((string)$engine['resource_name']) : '';
            if ($label === '') {
                continue;
            }

            $disclaimerId = isset($engine['resource_disclaimer']) ? absint($engine['resource_disclaimer']) : 0;
            $disclaimerUrl = '';
            if ($disclaimerId > 0) {
                $permalink = get_permalink($disclaimerId);
                if (is_string($permalink)) {
                    $disclaimerUrl = $permalink;
                }
            }

            $collection[] = [
                'id' => (string)$index,
                'label' => $label,
                'disclaimer_url' => $disclaimerUrl,
            ];
        }

        return $collection;
    }

    /**
     * Determine which engine should be selected for the current request.
     *
     * @param array<int, array<string, string>> $engines
     * @return string
     */
    private static function determinePreferredEngine(array $engines): string
    {
        if (empty($engines)) {
            return '';
        }

        $keys = array_column($engines, 'id');
        $keys = array_map('strval', $keys);

        if (isset($_GET['se'])) {
            $fromQuery = (string)absint($_GET['se']);
            if (in_array($fromQuery, $keys, true)) {
                return $fromQuery;
            }
        }

        if (isset($_COOKIE['rrze_search_engine_pref'])) {
            $fromCookie = (string)absint($_COOKIE['rrze_search_engine_pref']);
            if (in_array($fromCookie, $keys, true)) {
                return $fromCookie;
            }
        }

        return $keys[0];
    }

    /**
     * Resolve the configured multisearch results page URL.
     *
     * @param mixed $settings RRZE Search option array if already loaded.
     * @return string|null
     */
    private static function resolveResultsPageUrl($settings): ?string
    {
        if (!is_array($settings)) {
            $settings = get_option('rrze_search_settings');
        }

        if (!is_array($settings)) {
            return null;
        }

        $pageId = isset($settings['rrze_search_page_id']) ? absint($settings['rrze_search_page_id']) : 0;
        if ($pageId <= 0) {
            return null;
        }

        $permalink = get_permalink($pageId);
        if (is_string($permalink) && $permalink !== '') {
            return $permalink;
        }

        return null;
    }

    /**
     * Sanitize the target action URL.
     *
     * Rules:
     * - Allow only http/https absolute URLs or relative paths.
     * - Strip credentials, query and fragment.
     * - For relative paths, resolve against home_url().
     * - Fallback to home_url('/') on invalid input.
     *
     * @param string $raw Raw user-provided URL.
     * @return string Sanitized absolute URL.
     */
    private static function sanitizeActionUrl($raw): string
    {
        $clean = wp_strip_all_tags((string)$raw);
        // Remove control characters
        $clean = (string)preg_replace('/[\x00-\x1F\x7F]/', '', $clean);

        if ($clean === '') {
            return home_url('/');
        }

        // Relative path? -> normalize and join with home_url
        if (!preg_match('#^https?://#i', $clean)) {
            $path = '/' . ltrim($clean, '/');
            return home_url($path);
        }

        // Absolute URL: allow only http/https, drop user/pass, query, fragment
        $parts = wp_parse_url($clean);
        if (
            !is_array($parts) ||
            empty($parts['scheme']) ||
            !in_array(strtolower((string)$parts['scheme']), ['http', 'https'], true) ||
            empty($parts['host'])
        ) {
            return home_url('/');
        }

        $scheme = strtolower((string)$parts['scheme']);
        $host = (string)$parts['host'];
        $port = isset($parts['port']) ? ':' . (int)$parts['port'] : '';
        $path = isset($parts['path']) ? (string)$parts['path'] : '/';

        // Rebuild without user/pass, query, fragment
        return $scheme . '://' . $host . $port . $path;
    }

    /**
     * Sanitize the GET parameter name.
     *
     * Accepts:
     * - Optional prefix '?' or '&'
     * - Optional trailing '='
     * - Param name must match ^[A-Za-z][A-Za-z0-9._-]{0,63}$
     *
     * @param string $raw Raw user-provided parameter name.
     * @return string Safe parameter name.
     */
    private static function sanitizeParamName($raw): string
    {
        $clean = wp_strip_all_tags((string)$raw);
        $clean = (string)preg_replace('/[\x00-\x1F\x7F]/', '', $clean);
        $clean = trim($clean);

        if ($clean === '') {
            return self::DEFAULT_PARAM;
        }

        // Remove leading ? or & and trailing =
        $clean = (string)preg_replace('/^[?&]+/', '', $clean);
        $clean = (string)preg_replace('/=+$/', '', $clean);

        if (!preg_match('/^[A-Za-z][A-Za-z0-9._-]{0,63}$/', $clean)) {
            return self::DEFAULT_PARAM;
        }

        return $clean;
    }
}
