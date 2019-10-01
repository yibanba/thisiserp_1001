<?php

defined( 'ABSPATH' ) || exit;

// This function is a hook in the NBZhang class.
function admin_menus() {
    $nb_role_cap = get_nb_user_role();

    add_menu_page( '', '内部账系统', $nb_role_cap, 'nb-entrance', 'nb_entrance', '', 1 );
    add_submenu_page( 'nb-entrance', '内部账系统', '内部账系统', $nb_role_cap, 'nb-entrance', 'nb_entrance' );

    switch ( get_nb_user_role() ) {
        case 'nb_superman':
            menu_system_setup( $nb_role_cap );
            menu_overview( $nb_role_cap );
            menu_daily_biz( $nb_role_cap );
            menu_list_query( $nb_role_cap );
            break;
        case 'nb_operator':
            menu_overview( $nb_role_cap );
            menu_daily_biz( $nb_role_cap );
            menu_list_query( $nb_role_cap );
            break;
        case 'nb_manager':
            menu_overview( $nb_role_cap );
            menu_list_query( $nb_role_cap );
            break;
        case 'nb_auditor':
            menu_overview( $nb_role_cap );
            break;
        case 'nb_mobile':
            menu_mobile( $nb_role_cap );
            break;
        default:
            return admin_url();
    }
}

function nb_entrance() {
    admin_top_tabs( '类型', get_menu_title() );

    echo '共用入口';

}


/**
 * ########## ########## ########## ########## ########## ##########
 */

function menu_list_query( $role_cap ) {
    add_menu_page( '', '明细查询', $role_cap, 'daily-biz', null, null, 5 );
        add_submenu_page('daily-biz', '明细查询', '明细查询', $role_cap, 'list-query', 'list_query');
}

function menu_daily_biz( $role_cap ) {
    add_menu_page( '', '日常业务', $role_cap, 'daily-biz', null, null, 5 );
        add_submenu_page('daily-biz', '日常业务', '日常业务', $role_cap, 'daily-biz', 'daily_biz');
}


/**
 * Execute a common entry function and return the NB role(capability)
 */
function menu_mobile( $role_cap ) {
    add_menu_page( '', '内部账移动系统', $role_cap, 'nb-mobile', null, null, 7 );
        add_submenu_page( 'nb-mobile', '内部账移动系统', '内部账移动系统', $role_cap, 'nb-mobile', 'nb_mobile' );
}

function menu_overview( $role_cap ) {
    add_menu_page( '', '内部账说明', $role_cap, 'overview', null, null, 2 );
        add_submenu_page('overview', '内部账说明', '内部账说明', $role_cap, 'overview', 'overview');
        add_submenu_page('overview', '报表汇总', '报表汇总', $role_cap, 'report-summary', 'report_summary');
}

function menu_system_setup( $role_cap ) {
    add_menu_page( '', '内部账系统管理', $role_cap, 'nb-system', null, null, 1001 );
        add_submenu_page('nb-system', '用户手册', '用户手册', $role_cap, 'nb-system', 'user_manual');
        add_submenu_page('nb-system', '创建账套', '创建账套', $role_cap, 'create-account', 'create_account');
        add_submenu_page('nb-system', '登陆日志', '登陆日志', $role_cap, 'login-log', 'login_log');
        add_submenu_page('nb-system', '设置权限', '设置权限', $role_cap, 'set-capability', 'set_capability');
}
function create_account() {
    include_once ( NB_MODULES_PATH .  'core/create-account.php' );
}
function login_log() {
    include_once ( NB_MODULES_PATH .  'core/login-log.php' );
}
function set_capability() {
    include_once ( NB_MODULES_PATH .  'core/set-capability.php' );
}
function user_manual() {
    include_once ( NB_MODULES_PATH .  'core/user-manual.php' );
}

function overview() {
    ?>
    <div class="wrap">
        <h1>概览</h1>
        <?php
        global $menu;
        echo '1: 显示 nb- 前缀的菜单<br />';
        foreach ( $menu as $item ) {
            if( 'nb-' == substr( $item[2], 0, 3) ) {
                echo $item[2] . '<br />';
            }
        }
        echo '2: var_dump($menu)<br />';
        var_dump($menu);
        ?>
    </div>
    <?php
}
function report_summary() {
    admin_top_tabs( '类型', get_menu_title() );
    echo '报表汇总';
}

function daily_biz() {
    admin_top_tabs( '类型', get_menu_title() );
    echo '日常业务';
}


function nb_mobile() {
    echo '移动设备浏览信息';
}

/**
 * ########## ########## ########## ########## ########## ##########
 * Add a separator to the specified location
 * ########## ########## ########## ########## ########## ##########
add_action( 'admin_menu', function () {
    global $menu;
    $position = 3;

    // $separator = array('', 'read', 'separator' . $position, '', 'wp-menu-separator');
    $separator = [
        0 => '',
        1 => 'read',
        2 => 'separator' . $position,
        3 => '',
        4 => 'wp-menu-separator'
    ];
    if (isset($menu[$position])) {
        $menu = array_splice($menu, $position, 0, $separator);
    } else {
        $menu[$position] = $separator;
    }
});
 * ########## ########## ########## ########## ########## ##########
 */
