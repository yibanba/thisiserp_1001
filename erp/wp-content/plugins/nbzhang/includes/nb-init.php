<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'NB_Init' ) ) :
    class NB_Init {

        public static function init() {
            add_action( 'template_redirect',            array( __CLASS__, 'nb_login') );
            add_filter( 'login_redirect',               array( __CLASS__, 'nb_logged_in_redirect') , 10, 3 );
            add_action( 'wp_logout',                    array( __CLASS__, 'nb_logout') );
            add_action( 'wp_before_admin_bar_render',   array( __CLASS__, 'modify_admin_bar') );
            add_action( 'admin_init',                   array( __CLASS__, 'remove_menu') );
            add_filter( 'admin_footer_text',            array( __CLASS__, 'admin_footer_left_text') );
            add_filter( 'update_footer',                array( __CLASS__, 'admin_footer_right_text'), 11 );
            add_action( 'admin_menu',                   array( __CLASS__, 'wp_hide_nag' ) );
        }

        public static function nb_login() {
            if ( ! is_user_logged_in() ) {
                wp_safe_redirect( wp_login_url() );
            }
        }

        public static function nb_logged_in_redirect( $redirect_to, $request, $user ) {
            if ( isset( $user->roles ) && is_array($user->roles) ) {
                if ( array_intersect($user->roles, array_keys(NB_Install::$nb_roles) ) ) {
                    return admin_url( 'admin.php?page=nb-entrance' );
                }
            }
            return $redirect_to;
        }

        public static function nb_logout(){
            wp_safe_redirect( wp_login_url() );
        }

        /**
         * $wp_admin_bar->remove_menu('updates');      //移除升级通知
         * $wp_admin_bar->remove_menu('comments');     //移除评论
         * $wp_admin_bar->remove_menu('new-content');  // 移除“新建”
         * $wp_admin_bar->remove_menu('my-sites');   //移除我的网站(多站点)
         * $wp_admin_bar->remove_menu('search');     //移除搜索
         * $wp_admin_bar->remove_menu('my-account'); //移除个人中心
         * $wp_admin_bar->remove_menu('wp-logo');      //移除Logo
         * $wp_admin_bar->add_menu();  // 添加自定义菜单
         */

        public static function modify_admin_bar() {
            global $wp_admin_bar;

            if ( get_nb_user_role() ) {

                $wp_admin_bar->remove_menu('site-name');    //移除网站名称
                $wp_admin_bar->remove_node('dashboard');    //移除网站名称

                $wp_admin_bar->add_menu(array(
                    'id' => 'validity',
                    'title' => '服务器及数据库租用有效期',
                    'href' => ''
                ));
                $wp_admin_bar->add_menu(array(
                    'id' => 'date',
                    'title' => ' 2016-11-30 —— 2018-11-30  ',
                    'href' => '',
                    'parent' => 'validity'
                ));
            }
        }

        /**
         * remove_menu_page( 'jetpack' );                    //Jetpack*
         * remove_menu_page( 'edit.php' );                   //Posts
         * remove_menu_page( 'upload.php' );                 //Media
         * remove_menu_page( 'edit.php?post_type=page' );    //Pages
         * remove_menu_page( 'edit-comments.php' );          //Comments
         * remove_menu_page( 'themes.php' );                 //Appearance
         * remove_menu_page( 'plugins.php' );                //Plugins
         * remove_menu_page( 'users.php' );                  //Users
         * remove_menu_page( 'tools.php' );                  //Tools
         * remove_menu_page( 'options-general.php' );        //Settings
         */
        public static function remove_menu() {
            if ( get_nb_user_role() ) {
                remove_menu_page('index.php');              //Dashboard
                remove_menu_page('profile.php');            //profile
            }
        }

        // Replace the messages at the bottom
        public static function admin_footer_left_text( $text ) {
            $text = '<a href="https://www.nbzhang.com/">www.nbzhang.com</a> 内部账 - 财务管理系统';
            return '<span id="footer-thankyou">' . $text . '</span>';
        }

        public static function admin_footer_right_text( $text ) { // 右边信息
            $text = "QQ: 55517131 &nbsp; E-mail: sph999@hotmail.com";
            return $text;
        }


        public static function wp_hide_nag() {
            if ( get_nb_user_role() ) {
                remove_action('admin_notices', 'update_nag', 3);
            }
        }

    }

endif;
