<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'NB_Install' ) ) :
    class NB_Install {

        public static $nb_roles = array(
            'nb_superman'   => 'NBzhang - 系统管理',
            'nb_operator'   => 'NBzhang - 会计',
            'nb_manager'    => 'NBzhang - 经理',
            'nb_auditor'    => 'NBzhang - 审核',
            'nb_mobile'     => 'NBzhang - 移动设备'
        );


        public function __construct() {
            $this->add_nb_roles();
            $this->create_nb_tables();
        }

        private function add_nb_roles() {
            foreach ( self::$nb_roles as $role => $name ) {
                add_role( $role, $name, array('read' => true) );
            }
        }

        private function create_nb_tables() {
            global $wpdb;
            $wpdb->hide_errors();

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            $collate = '';

            if ( $wpdb->has_cap( 'collation' ) ) {
                $collate = $wpdb->get_charset_collate();
            }

            $tables_schema = "
CREATE TABLE {$wpdb->prefix}nb_accounts (
    na_id int(11) NOT NULL AUTO_INCREMENT,
    na_type varchar(100) NOT NULL,
    na_account varchar(100) NOT NULL,
    na_userid varchar(200) DEFAULT NULL,
    na_database longtext NOT NULL,
    na_startdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    na_updatedate datetime DEFAULT NULL,
    na_remark varchar(200) DEFAULT NULL,
    na_orderby int(11) NOT NULL DEFAULT '999',
    PRIMARY KEY (na_id)
) $collate;
CREATE TABLE {$wpdb->prefix}nb_loginlog (
    nl_id int(11) NOT NULL AUTO_INCREMENT,
    nl_userid int(11) NOT NULL,
    nl_ip varchar(50) NOT NULL,
    nl_account varchar(100) NOT NULL,
    nl_logintime datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (nl_id)
) $collate;
            ";

            dbDelta( $tables_schema );
        } // create_nb_tables

    }

endif;
