<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'NB_Notice' ) ) :
    class NB_Notice {
        public static function notice_plugin_activated() {
            $message = '您已成功激活<strong> 内部账-NBzhang </strong>插件，'
                . '请添加一个新用户，并设置为内部账<strong> NBzhang - 系统管理 </strong>角色，'
                . '然后使用管理员ID登陆，<strong> 内部账-NBzhang </strong>的所有功能均由此用户实现.<br />'
                . '当前没有用户角色被设置为：<strong> NBzhang - 系统管理 </strong><br />'
                . '<a href="' . admin_url('user-new.php') . '"><button class="button button-primary">添加用户</button></a>' . ' &nbsp; '
                . '<a href="' . admin_url('users.php') . '"><button class="button button-primary">编辑用户</button></a>';
            $class_type = 'warning';

            self::common_format( $message, $class_type );
        }

        public static function notice_add_superman() {
            $message = '您已成功添加一个新用户，并设置为内部账<strong> NBzhang - 系统管理 </strong>角色，请使用内部账管理员登陆进行后续操作.';
            $class_type = 'info';

            self::common_format( $message, $class_type );
        }

        public static function notice_superman_logged_in() {
                $message = '欢迎您使用<strong> 内部账-NBzhang </strong>，请按需要启用内部账相关功能并添加相应的操作人员.';
                $class_type = 'success';
                $is_dismissible = 1;

                self::common_format( $message, $class_type, $is_dismissible );
        }

        /**
         * CSS class_type:
         * notice-error     a white background and a red left border
         * notice-warning   a yellow/orange
         * notice-info:     a blue left border
         * notice-success   a green left border
         */
        public static function common_format( $message, $class_type, $is_dismissible='' ) {
            if ( $is_dismissible ) {
                $class = $class_type . ' is-dismissible';
            } else {
                $class = $class_type;
            }

            printf( '<div class="notice notice-%1$s"><p>%2$s</p></div>', $class, $message);
        }
    }
endif;
