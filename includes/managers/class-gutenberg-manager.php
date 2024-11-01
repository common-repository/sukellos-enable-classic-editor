<?php

namespace Sukellos;

defined( 'ABSPATH' ) or exit;

use Sukellos\WPFw\Singleton;

/**
 *  Gutenberg manager to centralize all functionality about Gutenberg
 *
 * The purpose of this class is to remove all Gutenberg roots...
 *
 * @since 1.0.0
 */
class Gutenberg_Manager {

    private static $settings;
    private static $supported_post_types = array();

    // Use Trait Singleton
    use Singleton;

    /**
     * Default init method called when instance created
     * This method can be overridden if needed.
     *
     * @since 1.0.0
     * @access protected
     */
    public function init() {

        // Check if gutenberg should be activated
        $enable_classic_editor = get_option( WP_Sukellos_Enable_Classic_Editor_Loader::instance()->get_options_suffix_param().'_checkbox_enable_classic_editor' );
        if ( !$enable_classic_editor ) {

            return;
        }

        $block_editor = has_action( 'enqueue_block_assets' );
        $gutenberg = function_exists( 'gutenberg_register_scripts_and_styles' );

        // Remove the "Try Gutenberg" dashboard widget.
        remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );

        if ( ! $block_editor && ! $gutenberg  ) {
            return;
        }

        // Disabling Block Editor functionality.
        add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );

        remove_theme_support( 'widgets-block-editor' );
        add_filter( 'use_block_editor_for_post', '__return_false' );
        add_filter( 'use_widgets_block_editor', '__return_false' );

        if ( $gutenberg ) {

            // Remove for older Gutenberg versions.
            add_filter( 'gutenberg_can_edit_post_type', '__return_false', 100 );

            remove_action( 'admin_menu', 'gutenberg_menu' );
            remove_action( 'admin_init', 'gutenberg_redirect_demo' );

            // Gutenberg 5.3+
            remove_action( 'wp_enqueue_scripts', 'gutenberg_register_scripts_and_styles' );
            remove_action( 'admin_enqueue_scripts', 'gutenberg_register_scripts_and_styles' );
            remove_action( 'admin_notices', 'gutenberg_wordpress_version_notice' );
            remove_action( 'rest_api_init', 'gutenberg_register_rest_widget_updater_routes' );
            remove_action( 'admin_print_styles', 'gutenberg_block_editor_admin_print_styles' );
            remove_action( 'admin_print_scripts', 'gutenberg_block_editor_admin_print_scripts' );
            remove_action( 'admin_print_footer_scripts', 'gutenberg_block_editor_admin_print_footer_scripts' );
            remove_action( 'admin_footer', 'gutenberg_block_editor_admin_footer' );
            remove_action( 'admin_enqueue_scripts', 'gutenberg_widgets_init' );
            remove_action( 'admin_notices', 'gutenberg_build_files_notice' );

            remove_filter( 'load_script_translation_file', 'gutenberg_override_translation_file' );
            remove_filter( 'block_editor_settings', 'gutenberg_extend_block_editor_styles' );
            remove_filter( 'default_content', 'gutenberg_default_demo_content' );
            remove_filter( 'default_title', 'gutenberg_default_demo_title' );
            remove_filter( 'block_editor_settings', 'gutenberg_legacy_widget_settings' );
            remove_filter( 'rest_request_after_callbacks', 'gutenberg_filter_oembed_result' );

            // Previously used, compat for older Gutenberg versions.
            remove_filter( 'wp_refresh_nonces', 'gutenberg_add_rest_nonce_to_heartbeat_response_headers' );
            remove_filter( 'get_edit_post_link', 'gutenberg_revisions_link_to_editor' );
            remove_filter( 'wp_prepare_revision_for_js', 'gutenberg_revisions_restore' );

            remove_action( 'rest_api_init', 'gutenberg_register_rest_routes' );
            remove_action( 'rest_api_init', 'gutenberg_add_taxonomy_visibility_field' );
            remove_filter( 'registered_post_type', 'gutenberg_register_post_prepare_functions' );

            remove_action( 'do_meta_boxes', 'gutenberg_meta_box_save' );
            remove_action( 'submitpost_box', 'gutenberg_intercept_meta_box_render' );
            remove_action( 'submitpage_box', 'gutenberg_intercept_meta_box_render' );
            remove_action( 'edit_page_form', 'gutenberg_intercept_meta_box_render' );
            remove_action( 'edit_form_advanced', 'gutenberg_intercept_meta_box_render' );
            remove_filter( 'redirect_post_location', 'gutenberg_meta_box_save_redirect' );
            remove_filter( 'filter_gutenberg_meta_boxes', 'gutenberg_filter_meta_boxes' );

            remove_filter( 'body_class', 'gutenberg_add_responsive_body_class' );
            remove_filter( 'admin_url', 'gutenberg_modify_add_new_button_url' ); // old
            remove_action( 'admin_enqueue_scripts', 'gutenberg_check_if_classic_needs_warning_about_blocks' );
            remove_filter( 'register_post_type_args', 'gutenberg_filter_post_type_labels' );

            // Older, not used in 5.3+.
            remove_action( 'admin_init', 'gutenberg_add_edit_link_filters' );
            remove_action( 'admin_print_scripts-edit.php', 'gutenberg_replace_default_add_new_button' );
            remove_filter( 'redirect_post_location', 'gutenberg_redirect_to_classic_editor_when_saving_posts' );
            remove_filter( 'display_post_states', 'gutenberg_add_gutenberg_post_state' );
            remove_action( 'edit_form_top', 'gutenberg_remember_classic_editor_when_saving_posts' );
        }

        if ( $block_editor ) {

            // Move the Privacy Page notice back under the title.
            add_action( 'admin_init', array( $this, 'action_admin_init' ) );
        }
    }

    /**
     *          ===============
     *      =======================
     *  ============ HOOKS ===========
     *      =======================
     *          ===============
     */

    public function action_admin_init() {

        global $pagenow;

        if ( $pagenow !== 'post.php' ) {
            return;
        }

        $post_id = 0;
        if ( !empty( $_GET['post'] ) && !empty( $_GET['action'] ) && $_GET['action'] === 'edit' && !empty( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] === 'post.php' ) {

            $post_id = (int) $_GET['post'];
        }

        if ( $post_id ) {

            remove_action( 'admin_notices', array( 'WP_Privacy_Policy_Content', 'notice' ) );
            add_action( 'edit_form_after_title', array( 'WP_Privacy_Policy_Content', 'notice' ) );
        }
    }

}
