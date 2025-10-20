<?php

namespace RRZE\RRZESearch\Infrastructure;
defined( 'ABSPATH' ) || exit;

use Exception;

/**
 * Handles Block Registration, Localization and Rendering of the FAUdir Block.
 */
class BlockRegistration {
    public function __construct() {
        add_action('init', [$this, 'rrze_search_block_init'], 15);
        add_filter('block_categories_all', [$this, 'register_rrze_block_category'], 10, 2);
    }

    /**
     * Register the rrze_search Block and initialize the l10n.
     */
    public function rrze_search_block_init(): void {
        $this->rrze_register_blocks();
        //$this->rrze_register_translations();
    }

    /**
     * Register the rrze_search block for the BlockEditor
     */
    public function rrze_register_blocks(): void {
        register_block_type(plugin_dir_path( dirname( __DIR__ ) ) . 'build/block', [
            'render_callback' => [$this, 'render_rrze_search_block'],
            'skip_inner_blocks' => true
        ]);
//        $scriptHandle = generate_block_asset_handle('rrze-search/block', 'editorScript');
//        wp_set_script_translations(
//            $scriptHandle,
//            'rrze-search',
//            plugin_dir_path(__DIR__) . 'languages'
//        );
//        load_plugin_textdomain(
//            'rrze-search',
//            false,
//            dirname(plugin_basename(__DIR__)) . '/languages'
//        );
    }

    /**
     * Adds custom block category if not already present.
     *
     * @param array $categories Existing block categories.
     * @param WP_Post $post Current post object.
     * @return array Modified block categories.
     */
    public static function register_rrze_block_category($categories, $post): array
    {
        // Check if there is already a RRZE category present
        foreach ($categories as $category) {
            if (isset($category['slug']) && $category['slug'] === 'rrze') {
                return $categories;
            }
        }

        $custom_category = [
            'slug' => 'fau',
            'title' => __('FAU', 'rrze-search'),
        ];

        $categories[] = $custom_category;

        return $categories;
    }

    /**
     * Render and Process the dynamic rrze_search Block
     * @param $attributes
     * @return string The Shortcode Output | An error message if no shortcode is present.
     */
    public static function render_rrze_search_block($attributes): string {
        try {
            return 'Hello World!';

        } catch (Exception $e) {
            return sprintf(
                '<div class="rrze-search-error">%s</div>',
                esc_html($e->getMessage())
            );
        }
    }
}