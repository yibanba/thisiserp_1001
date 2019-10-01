<?php
defined( 'ABSPATH' ) || exit;

function get_nb_user_role() {
    global $current_user;

    if ( in_array( $current_user->roles[0], array_keys( NB_Install::$nb_roles ) ) ) {
        return $current_user->roles[0];
    }
    return false;
}

function is_added_superman() {
    $users = get_users();
    foreach ( $users as $user ) {
        if ( 'nb_superman' == $user->roles[0] ) {
            return true;
        }
    }
    return false;
}

function is_superman_logged_in() {
    global $current_user;
    if ( isset( $current_user->roles[0]) && 'nb_superman' == $current_user->roles[0] ) {
        return true;
    }
    return false;
}

/**
 * Use $menu_slug(URL) to find $menu_title
 */
function get_menu_title() {
    global $menu, $submenu;
    $menu_title = '';
    foreach ( $menu as $m ) {
        if( $_GET['page'] == $m[2] ) {
            $menu_title = $m[0];
            break;
        }
    }
    foreach ( $submenu as $parent_slug ) {
        foreach ( $parent_slug as $m) {
            if( $_GET['page'] == $m[2] ) {
                $menu_title = $m[0];
                break;
            }
        }
    }
    return $menu_title;
}

function admin_top_tabs( $account='', $menu_title='' ) {
    $nb_entrance    = admin_url( 'index.php?page=nb-entrance' );
    $nb_loggout     = wp_logout_url();

    if ( $account == '') {
        $tab_active = sprintf ( '<a href="%1$s" class="nav-tab nav-tab-active">内部账系统入口</a>', $nb_entrance );
    } else {
        $tab_active = sprintf (
            '<a href="%1$s" class="nav-tab">内部账系统入口 &#187 %2$s</a>
            <a href="" class="nav-tab nav-tab-active">%3$s</a>'
            , $nb_entrance, $account, $menu_title
        );
    }

    printf (
'<nav class="nav-tab-wrapper wp-clearfix">
            <div class="view-switch"><a href="%1$s" class="view-list current" style="line-height: 32px"></a></div>
            %2$s
            <a href="%3$s" class="nav-tab">退出系统</a>
        </nav>'
        , $nb_entrance, $tab_active, $nb_loggout
    );
}