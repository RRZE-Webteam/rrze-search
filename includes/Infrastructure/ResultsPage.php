<?php

namespace RRZE\RRZESearch\Infrastructure;

defined('ABSPATH') || exit;

use WP_Error;
use WP_Post;

/**
 * Manages the WordPress page used to render RRZE Search results.
 *
 * The page remains editable so site editors can add their own introductory
 * content and layout. RRZE Search owns its lifecycle, however, and makes sure
 * that the configured page remains available while the plugin is active.
 */
final class ResultsPage
{
    public const OPTION_NAME = 'rrze_search_settings';

    public const OPTION_KEY = 'rrze_search_page_id';

    public const META_KEY = '_rrze_search_managed_page';

    public const SHORTCODE = 'rrze_search_results';

    private const DEFAULT_SLUG = 'rrze_search_page';

    private static ?WP_Error $lastError = null;

    private static ?int $ensuredPageId = null;

    private static bool $isEnsuring = false;

    /**
     * Registers lifecycle protection and administration helpers.
     */
    public function register(): void
    {
        add_action('init', [self::class, 'ensureExists'], 20);
        add_filter('pre_trash_post', [self::class, 'protectFromDeletion'], 10, 3);
        add_filter('pre_delete_post', [self::class, 'protectFromDeletion'], 10, 3);
        add_filter('map_meta_cap', [self::class, 'filterMetaCapabilities'], 10, 4);
        add_filter('page_row_actions', [self::class, 'filterRowActions'], 10, 2);
        add_filter('display_post_states', [self::class, 'addPostState'], 10, 2);
        add_action('admin_notices', [self::class, 'renderAdminNotices']);
    }

    /**
     * Returns the configured page ID, or zero if no page has been stored.
     */
    public static function getId(): int
    {
        $settings = get_option(self::OPTION_NAME);

        if (!is_array($settings)) {
            return 0;
        }

        return isset($settings[self::OPTION_KEY]) ? absint($settings[self::OPTION_KEY]) : 0;
    }

    /**
     * Ensures that one published, marked results page exists.
     *
     * Existing installations are migrated in place by marking the page already
     * referenced in the plugin option. A previously trashed managed page is
     * restored instead of creating a duplicate.
     *
     * @return int|WP_Error Managed page ID or an error when creation/restoration failed.
     */
    public static function ensureExists(): int|WP_Error
    {
        if (self::$ensuredPageId !== null) {
            return self::$ensuredPageId;
        }

        if (self::$isEnsuring) {
            $pageId = self::getId();

            return $pageId > 0
                ? $pageId
                : new WP_Error('rrze_search_results_page_recursion', __('The search results page is currently being created.', 'rrze-search'));
        }

        self::$isEnsuring = true;

        try {
            $page = self::getConfiguredPage();

            if (!$page instanceof WP_Post) {
                $page = self::findMarkedPage();
            }

            if (!$page instanceof WP_Post) {
                $createdPageId = self::createPage();
                if (!is_wp_error($createdPageId)) {
                    self::$ensuredPageId = $createdPageId;
                }

                return $createdPageId;
            }

            update_post_meta($page->ID, self::META_KEY, '1');

            if ($page->post_status === 'trash') {
                $restored = wp_untrash_post($page->ID);
                if (!$restored) {
                    return self::setError(new WP_Error(
                        'rrze_search_results_page_restore_failed',
                        __('The RRZE Search results page could not be restored from the Trash.', 'rrze-search')
                    ));
                }

                $page = get_post($page->ID);
            }

            if (!$page instanceof WP_Post) {
                return self::setError(new WP_Error(
                    'rrze_search_results_page_missing',
                    __('The RRZE Search results page could not be loaded.', 'rrze-search')
                ));
            }

            if ($page->post_status !== 'publish') {
                $updated = wp_update_post([
                    'ID' => $page->ID,
                    'post_status' => 'publish',
                ], true);

                if (is_wp_error($updated)) {
                    return self::setError($updated);
                }
            }

            self::storePageId($page->ID);
            self::$ensuredPageId = $page->ID;
            self::$lastError = null;

            return $page->ID;
        } finally {
            self::$isEnsuring = false;
        }
    }

    /**
     * Returns the canonical URL of the managed results page.
     */
    public static function getUrl(): ?string
    {
        $pageId = self::ensureExists();
        if (is_wp_error($pageId)) {
            return null;
        }

        $permalink = get_permalink($pageId);

        return is_string($permalink) && $permalink !== '' ? $permalink : null;
    }

    /**
     * Changes the status of the configured page without creating a new page.
     *
     * Used during deactivation, when the managed output should no longer be
     * public but the customizable page must remain intact.
     */
    public static function setStatus(string $status): int|WP_Error
    {
        $page = self::getConfiguredPage();
        if (!$page instanceof WP_Post) {
            return 0;
        }

        return wp_update_post([
            'ID' => $page->ID,
            'post_status' => $status,
        ], true);
    }

    /**
     * Prevents the managed page from being trashed or permanently deleted.
     *
     * @param mixed   $delete Short-circuit value supplied by WordPress.
     * @param WP_Post $post   Post considered for deletion.
     * @param mixed   $context Previous status or force-delete flag.
     * @return mixed
     */
    public static function protectFromDeletion($delete, $post, $context = null)
    {
        unset($context);

        if ($post instanceof WP_Post && self::isManaged($post)) {
            return false;
        }

        return $delete;
    }

    /**
     * Denies delete capabilities for the system page in the UI and REST API.
     *
     * @param string[] $caps Primitive capabilities required by WordPress.
     * @param mixed[]  $args Additional capability arguments; the first is the post ID.
     * @return string[]
     */
    public static function filterMetaCapabilities(array $caps, string $cap, int $userId, array $args): array
    {
        unset($userId);

        if ($cap !== 'delete_post' || empty($args[0])) {
            return $caps;
        }

        $post = get_post(absint($args[0]));
        if ($post instanceof WP_Post && self::isManaged($post)) {
            return ['do_not_allow'];
        }

        return $caps;
    }

    /**
     * Removes the Trash link from the managed page's row actions.
     *
     * @param array<string,string> $actions
     * @return array<string,string>
     */
    public static function filterRowActions(array $actions, WP_Post $post): array
    {
        if (self::isManaged($post)) {
            unset($actions['trash'], $actions['delete']);
        }

        return $actions;
    }

    /**
     * Labels the page as plugin-managed in Pages > All Pages.
     *
     * @param array<string,string> $postStates
     * @return array<string,string>
     */
    public static function addPostState(array $postStates, WP_Post $post): array
    {
        if (self::isManaged($post)) {
            $postStates['rrze_search_results_page'] = __('RRZE Search system page', 'rrze-search');
        }

        return $postStates;
    }

    /**
     * Warns administrators about lifecycle errors and a removed shortcode.
     */
    public static function renderAdminNotices(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (self::$lastError instanceof WP_Error) {
            echo '<div class="notice notice-error"><p>'
                . esc_html(self::$lastError->get_error_message())
                . '</p></div>';

            return;
        }

        $page = self::getConfiguredPage();
        if (!$page instanceof WP_Post || has_shortcode($page->post_content, self::SHORTCODE)) {
            return;
        }

        $editUrl = get_edit_post_link($page->ID, 'raw');
        $message = __('The RRZE Search system page no longer contains the results shortcode. Add [rrze_search_results] to the page so that search results can be displayed.', 'rrze-search');

        echo '<div class="notice notice-warning"><p>' . esc_html($message);
        if (is_string($editUrl) && $editUrl !== '') {
            echo ' <a href="' . esc_url($editUrl) . '">' . esc_html__('Edit search results page', 'rrze-search') . '</a>';
        }
        echo '</p></div>';
    }

    /**
     * Returns whether a post is the managed RRZE Search page.
     */
    public static function isManaged(WP_Post $post): bool
    {
        if ($post->post_type !== 'page') {
            return false;
        }

        return $post->ID === self::getId()
            || get_post_meta($post->ID, self::META_KEY, true) === '1';
    }

    private static function getConfiguredPage(): ?WP_Post
    {
        $pageId = self::getId();
        if ($pageId <= 0) {
            return null;
        }

        $page = get_post($pageId);
        if (!$page instanceof WP_Post) {
            return null;
        }

        if ($page->post_type !== 'page') {
            return null;
        }

        return $page;
    }

    private static function findMarkedPage(): ?WP_Post
    {
        $pageIds = get_posts([
            'post_type' => 'page',
            'post_status' => ['publish', 'future', 'draft', 'pending', 'private', 'trash'],
            'posts_per_page' => 1,
            'orderby' => 'ID',
            'order' => 'ASC',
            'fields' => 'ids',
            'meta_key' => self::META_KEY,
            'meta_value' => '1',
            'suppress_filters' => true,
        ]);

        if (empty($pageIds)) {
            return null;
        }

        $page = get_post((int) reset($pageIds));

        return $page instanceof WP_Post ? $page : null;
    }

    private static function createPage(): int|WP_Error
    {
        $pageId = wp_insert_post([
            'post_content' => '[' . self::SHORTCODE . ']',
            'post_name' => self::DEFAULT_SLUG,
            'post_title' => __('Global Search Results', 'rrze-search'),
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_excerpt' => __('Search Result Page utilized by RRZE Search Plugin', 'rrze-search'),
            'meta_input' => [
                self::META_KEY => '1',
            ],
        ], true);

        if (is_wp_error($pageId)) {
            return self::setError($pageId);
        }

        self::storePageId($pageId);
        self::$lastError = null;

        return $pageId;
    }

    private static function storePageId(int $pageId): void
    {
        $settings = get_option(self::OPTION_NAME);
        if (!is_array($settings)) {
            $settings = [];
        }

        if (isset($settings[self::OPTION_KEY]) && absint($settings[self::OPTION_KEY]) === $pageId) {
            return;
        }

        $settings[self::OPTION_KEY] = $pageId;
        update_option(self::OPTION_NAME, $settings, true);
    }

    private static function setError(WP_Error $error): WP_Error
    {
        self::$lastError = $error;

        return $error;
    }
}
