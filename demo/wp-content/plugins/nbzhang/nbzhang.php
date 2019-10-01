<?php

/**
 *
 * Plugin Name:     内部账 - NBzhang (Internal Account) is an ERP Lite plugin for WordPress.
 * Plugin URI:      https://demo.nbzhang.net/
 * Description:     精简版ERP，包括进销存、生产核算以及客户管理
 * Author:          Victor Sun
 * Author URI:      https://www.yibanba.com/
 */

defined( 'ABSPATH' ) || exit;

// Define NB_PLUGIN_FILE.
if ( ! defined( 'NB_PLUGIN_FILE' ) ) {
    define( 'NB_PLUGIN_FILE', __FILE__ );
}

if ( ! class_exists( 'NBzhang' ) ) :

    // Main Class
    final class NBzhang {

        public $version     = '1.0.0';

        public static function instance() {
            static $instance = null;

            if ( null === $instance ) {
                $instance = new NBzhang;
            }

            return $instance;
        }

        private function __construct() {
            $this->define_constants();
            $this->includes();
            $this->setup_actions();
        }

        private function define_constants() {
            /**
             * NB_PATH:             /xxx/wp-content/plugins/nbzhang/
             * NB_URL:              http://xxx/wp-content/plugins/nbzhang/
             * NB_INCLUDES_PATH:    /xxx/wp-content/plugins/nbzhang/includes/
             * NB_MODULES_PATH:     /xxx/wp-content/plugins/nbzhang/modules/
             * NB_VENDORS_PATH:     /xxx/wp-content/plugins/nbzhang/vendors/
             * NB_UPLOADS_PATH:     /xxx/wp-content/uploads/nb-uploads/
             */
            define( 'NB_PATH',          plugin_dir_path( NB_PLUGIN_FILE ) );
            define( 'NB_URL',           plugin_dir_url ( NB_PLUGIN_FILE ) );
            define( 'NB_INCLUDES_PATH', NB_PATH . 'includes/' );
            define( 'NB_MODULES_PATH',  NB_PATH . 'modules/' );
            define( 'NB_VENDORS_PATH',  NB_PATH . 'vendors/' );

            //This plugin specific upload folder
            $wp_upload_dir = wp_get_upload_dir();
            $nb_upload_dir = $wp_upload_dir['basedir'] . '/nb-uploads';
            if ( file_exists ( $nb_upload_dir ) || wp_mkdir_p( $nb_upload_dir ) ) {
                define( 'NB_UPLOADS_PATH',  $nb_upload_dir );
            }

            // Enable $_SESSION
            add_action('init', array($this, 'sessionStart'), 1);
            add_action('wp_logout', array($this, 'sessionEnd'));
            add_action('wp_login', array($this, 'sessionEnd'));
        }

        private function includes() {
            include_once( NB_INCLUDES_PATH  . 'nb-functions.php' );
            include_once( NB_INCLUDES_PATH  . 'nb-install.php' );
            include_once( NB_INCLUDES_PATH  . 'nb-notices.php' );
            include_once( NB_INCLUDES_PATH  . 'nb-init.php' );
            include_once( NB_INCLUDES_PATH  . 'admin/admin-menus.php' );
        }

        private function setup_actions() {
            register_activation_hook( __FILE__, array( $this, 'activate' ) );

            add_action( 'admin_notices', array( $this, 'admin_notice') );
            add_action('init', array( $this, 'check_install_status'));
            add_action('init', array( $this, 'init'));

            add_action( 'admin_menu', 'admin_menus');
        }

        public function activate() {
            if ( ! get_option( 'NB_INSTALLED' ) ) {
                new NB_Install();
                /**
                 * the NBzhang trilogy: NB_Installing_Status == ( -1, 0, 1 )
                 * Activation plugin - NBzhang      : -1
                 * Add & Set nb_superman role             : 0
                 * The nb_superman first logged in  : 1
                 * Setting different states will display different NB_Notices
                 */
                update_option( 'NB_Installing_Status',  -1 );
            }
        }

        /**
         * SESSION ... session_start && session_destroy
         */
        public function sessionStart() {
            if ( ! session_id() ) {
                session_start();
            }
        }

        public function sessionEnd() {
            session_destroy();
        }

        public function check_install_status() {
            // Warning: Note the order of conditional statements, from large to small!
            if ( is_superman_logged_in() ) {
                update_option( 'NB_Installing_Status', 1 );
            } elseif ( is_added_superman() ) {
                update_option( 'NB_Installing_Status', 0 );
            }
        }

        public function admin_notice() {
            if ( 1 == get_option( 'NB_Installed' ) ) {
                return;
            }

            switch ( get_option( 'NB_Installing_Status' ) ) {
                case -1:
                    NB_Notice::notice_plugin_activated();
                    break;
                case 0:
                    NB_Notice::notice_add_superman();
                    break;
                case 1:
                    NB_Notice::notice_superman_logged_in();
                    // not break;
                default:
                    // If the superman has logged in, it means the plugin install complete
                    update_option( 'NB_Installed', 1 );
            }
        }

        public function init() {
            NB_Init::init();
        }
    }

    // return The one true NBzhang Instance
    function nb() {
        return NBzhang::instance();
    }

    nb();

endif; // class_exists check
