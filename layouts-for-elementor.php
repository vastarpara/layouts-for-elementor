<?php

/**
 * Plugin Name: Layouts for Elementor
 * Plugin URI: https://profiles.wordpress.org/giraphix/
 * Description: Beautifully designed, Free templates, Handcrafted for popular Elementor page builder.
 * Version: 1.2.4
 * Author: Giraphix Creative
 * Author URI: https://giraphixcreative.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: layouts-for-elementor
 * Domain Path: /languages/
 */
/*
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/*
 * Define variables
 */
define('LFE_FILE', __FILE__);
define('LFE_DIR', plugin_dir_path(LFE_FILE));
define('LFE_URL', plugins_url('/', LFE_FILE));
define('LFE_TEXTDOMAIN', 'layouts-for-elementor');

/**
 * Main Plugin Layout_For_Elementor class.
 */
class Layout_For_Elementor {

    /**
     * Layout_For_Elementor constructor.
     *
     * The main plugin actions registered for WordPress
     */
    public function __construct() {
        add_action('init', array($this, 'lfe_check_dependencies'));
        $this->hooks();
        $this->lfe_include_files();
    }

    /**
     * Initialize
     */
    public function hooks() {
        add_action('plugins_loaded', array($this, 'lfe_load_language_files'));
        add_action('admin_enqueue_scripts', array($this, 'lfe_admin_scripts',));
    }

    /**
     * Load files
     */
    public function lfe_include_files() {
        if (did_action('elementor/loaded')) {
            include_once( LFE_DIR . 'includes/class-layout-importer.php' );
            include_once( LFE_DIR . 'includes/api/class-layouts-remote.php' );
        }    
    }

    /**
     * @return Loads plugin textdomain
     */
    public function lfe_load_language_files() {
        load_plugin_textdomain(LFE_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Check plugin dependencies
     * Check if Elementor plugin is installed
     */
    public function lfe_check_dependencies() {

        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', array($this, 'lfe_layouts_widget_fail_load'));
            return;
        } else {
            add_action('admin_menu', array($this, 'lfe_menu'));
        }
        $elementor_version_required = '1.1.2';
        if (!version_compare(ELEMENTOR_VERSION, $elementor_version_required, '>=')) {
            add_action('admin_notices', array($this, 'lfe_layouts_elementor_update_notice'));
            return;
        }
    }

    /**
     * This notice will appear if Elementor is not installed or activated or both
     */
    public function lfe_layouts_widget_fail_load() {

        $screen = get_current_screen();
        if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
            return;
        }

        $plugin = 'elementor/elementor.php';
        $file_path = 'elementor/elementor.php';
        $installed_plugins = get_plugins();

        if (isset($installed_plugins[$file_path])) { // check if plugin is installed
            if (!current_user_can('activate_plugins')) {
                return;
            }
            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);

            $message = '<p><strong>' . __('Layouts for Elementor', LFE_TEXTDOMAIN) . '</strong>' . __(' widgets not working because you need to activate the Elementor plugin.', LFE_TEXTDOMAIN) . '</p>';
            $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, __('Activate Elementor Now', LFE_TEXTDOMAIN)) . '</p>';
        } else {
            if (!current_user_can('install_plugins')) {
                return;
            }

            $install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');

            $message = '<p><strong>' . __('Layouts for Elementor', LFE_TEXTDOMAIN) . '</strong>' . __(' widgets not working because you need to install the Elemenor plugin', LFE_TEXTDOMAIN) . '</p>';
            $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $install_url, __('Install Elementor Now', LFE_TEXTDOMAIN)) . '</p>';
        }

        echo '<div class="error"><p>' . $message . '</p></div>';
    }

    /**
     * Display admin notice for Elementor update if Elementor version is old
     */
    public function lfe_layouts_elementor_update_notice() {
        if (!current_user_can('update_plugins')) {
            return;
        }

        $file_path = 'elementor/elementor.php';

        $upgrade_link = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file_path, 'upgrade-plugin_' . $file_path);
        $message = '<p><strong>' . __('Layouts for Elementor', LFE_TEXTDOMAIN) . '</strong>' . __(' widgets not working because you are using an old version of Elementor.', CTW_DOMAIN) . '</p>';
        $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $upgrade_link, __('Update Elementor Now', CTW_DOMAIN)) . '</p>';
        echo '<div class="error">' . $message . '</div>';
    }

    /**
     *
     * @return Enqueue admin panel required css/js
     */
    public function lfe_admin_scripts() {
        $screen = get_current_screen();

        wp_register_style('lfe-admin-stylesheets', LFE_URL . 'assets/css/admin.css');
        wp_register_style('lfe-toastify-stylesheets', LFE_URL . 'assets/css/toastify.css');
        wp_register_script('lfe-admin-script', LFE_URL . 'assets/js/admin.js', array('jquery'), false, true);
        wp_register_script('lfe-toastify-script', LFE_URL . 'assets/js/toastify.js', array('jquery'), false, true);
        wp_localize_script('lfe-admin-script', 'js_object', array(
            'lfe_loading' => __('Importing...', LFE_TEXTDOMAIN),
            'lfe_msg' => __('Your page is successfully imported!', LFE_TEXTDOMAIN),
            'lfe_crt_page' => __('Please Enter Page Name.', LFE_TEXTDOMAIN),
            'lfe_sync' => __('Syncing...', LFE_TEXTDOMAIN),
            'lfe_sync_suc' => __('Templates library refreshed', LFE_TEXTDOMAIN),
            'lfe_sync_fai' => __('Error in library Syncing', LFE_TEXTDOMAIN),
            'lfe_url' => LFE_URL,
                )
        );

        if ((isset($_GET['page']) && ( $_GET['page'] == 'lfe_layouts' || $_GET['page'] == 'lfe_started'))) {
            wp_enqueue_style('lfe-admin-stylesheets');
            wp_enqueue_style('lfe-toastify-stylesheets');
            wp_enqueue_script('lfe-toastify-script');
            wp_enqueue_script('lfe-admin-script');
            wp_enqueue_script('lfe-admin-live-script');
            add_thickbox();
        }
    }

    /**
     *
     * add menu at admin panel
     */
    public function lfe_menu() {
        add_menu_page(__('Layouts', LFE_TEXTDOMAIN), __('Layouts', LFE_TEXTDOMAIN), 'administrator', 'lfe_layouts', 'lfe_layouts_function', LFE_URL . 'assets/images/layouts-for-elementor.png');

        /**
         *
         * @global type $wp_version
         * @return html Display setting options
         */
        function lfe_layouts_function() {
            include_once( 'includes/layouts.php' );
        }

    }

}

/*
 * Starts our plugin class, easy!
 */
new Layout_For_Elementor();
