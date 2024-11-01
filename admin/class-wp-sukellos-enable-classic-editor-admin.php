<?php

namespace Sukellos\Admin;

use Sukellos\WP_Sukellos_Enable_Classic_Editor_Loader;
use Sukellos\WPFw\Singleton;
use Sukellos\WPFw\AdminBuilder\Admin_Builder;
use Sukellos\WPFw\WP_Plugin_Admin;
use Sukellos\WPFw\Utils\WP_Log;
use Sukellos\WPFw\AdminBuilder\Item_Type;
use Sukellos\WPFw\Utils\WP_Helper;

defined( 'ABSPATH' ) or exit;

/**
 * Admin class.
 * Main admin is used as controller to init admin menu and all other admin pages
 *
 * @since 1.0.0
 */
class WP_Sukellos_Enable_Classic_Editor_Admin extends WP_Plugin_Admin {

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

        parent::init();

        // Add action to delegate settings fields creation to Sukellos Fw Tools admin
        // Use priority to order Tools
        add_action( 'sukellos_fw/admin/create_tools_fields', array( $this, 'action_create_tools_fields' ), 10, 1 );


        WP_Log::info( 'WP_Sukellos_Enable_Classic_Editor_Admin->init OK!',[], WP_Sukellos_Enable_Classic_Editor_Loader::instance()->get_text_domain());
    }


    /**
     * Gets the plugin configuration URL
     * This is used to build actions list in plugins page
     * Leave blank ('') to disable
     *
     * @since 1.0.0
     *
     * @return string plugin settings URL
     */
    public function get_settings_url() {

        return admin_url( 'admin.php?page='.WP_Sukellos_Enable_Classic_Editor_Loader::instance()->get_options_suffix_param().'_tools' );
    }

    /**
     *          ===============
     *      =======================
     *  ============ HOOKS ===========
     *      =======================
     *          ===============
     */


    /***
     * Adding CSS and JS into header
     * Default add assets/admin.css and assets/admin.js
     */
    public function admin_enqueue_scripts() {}


    /***
     * Admin page
     * Settings managed by main Sukellos Fw Tools admin
     */
    public function create_items() {}

    /**
     * Tools fields creation
     */
    public function action_create_tools_fields( $admin_page ) {

        // Admin page is a Tabs page
        $admin_tab = $admin_page->create_tab(
            array(
                'id' => WP_Sukellos_Enable_Classic_Editor_Loader::instance()->get_options_suffix_param().'_gutenberg_tab',
                'name' => WP_Helper::sk__('Enable Classic Editor' ),
                'desc' => '',
            )
        );

        // Create a header
        $admin_tab->create_header(
            array(
                'id' => WP_Sukellos_Enable_Classic_Editor_Loader::instance()->get_options_suffix_param().'_header_gutenberg',
                'name' => WP_Helper::sk__('Classic Editor' ),
                'desc' => WP_Helper::sk__( 'Switch back to classic editor in post types and widgets' ),
            )
        );

        // Create a enable option field
        $admin_tab->create_option(
            array(
                'type' => Item_Type::ENABLE,
                // Common
                'id' => WP_Sukellos_Enable_Classic_Editor_Loader::instance()->get_options_suffix_param().'_checkbox_enable_classic_editor',
                'name' => WP_Helper::sk__('Enable classic editor' ),
                'desc' => WP_Helper::sk__('Enable to activate the classic editor block manager instead of the Gutenberg editor' ),
                // Specific
                'enabled' => 'On',
                'disabled' => 'Off',
            )
        );
    }
}