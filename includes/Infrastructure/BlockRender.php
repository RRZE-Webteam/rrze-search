<?php

namespace RRZE\RRZESearch\Infrastructure;
use RRZE\RRZESearch\Application\Widget\SearchWidget;

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

        $rawTargetUrl = $attributes['searchTargetUrl'] ?? '';
        $actionUrl = self::sanitizeActionUrl($rawTargetUrl);

        $rawParam = $attributes['searchGetParameter'] ?? '';
        $paramName = self::sanitizeParamName($rawParam);

        // Feature flags based on width
        $showSearchScope = ($width === 'content-size'); // reserved for future use
        $enableAdvancedFeatures = ($width === 'content-size');

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
        }

        // ---- Build output ------------------------------------------------
        ob_start();
        include dirname(__DIR__).'/Infrastructure/Templates/partials/search-form.php';
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
                        method="get"
                        action="<?php echo esc_url($actionUrl); ?>"
                        id="<?php echo esc_attr($formId); ?>"
                    <?php if ($enableAdvancedFeatures) : ?>
                        data-advanced-features="true" data-enable-autocomplete="true"
                    <?php endif; ?>
                >
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
                </form>
            </div>
        </div>
        <?php
        return (string)ob_get_clean();
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