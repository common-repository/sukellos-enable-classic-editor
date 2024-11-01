<?php

namespace Sukellos;

use Sukellos\WPFw\Utils\WP_Log;
use Sukellos\WPFw\WP_Plugin;
use Sukellos\WPFw\Singleton;

defined( 'ABSPATH' ) or exit;

/**
 * Wordpress Sukellos Enable Classic Editor plugin class.
 *
 * @since 1.0.0
 */
class WP_Sukellos_Enable_Classic_Editor extends WP_Plugin {

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

        // Load text domain for translations
        load_plugin_textdomain( WP_Sukellos_Enable_Classic_Editor_Loader::instance()->get_text_domain(), false, WP_Sukellos_Enable_Classic_Editor_Loader::instance()->get_text_domain().'/languages/' );

        self::$instance->enable_admin();

        // Deactivate Gutenberg
        Gutenberg_Manager::instance();

        WP_Log::info( 'WP_Sukellos_Fw init OK!' );
    }

    /**
     * Initializes the custom post types.
     * Called on init and on activation hooks
     *
     * Must be override to create custom post types for the plugin
     *
     * @since 1.0.0
     */
    public function init_custom_post_types() {

        // No custom post types...
    }

    /**
     *          ===============
     *      =======================
     *  ============ HOOKS ===========
     *      =======================
     *          ===============
     */


}
