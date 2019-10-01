<?php
/**
 * Plugin Name:     TEST
 */

// 临时测试菜单
add_action( 'admin_menu', 'backend');
function backend() {
    add_submenu_page( 'index.php', '后台管理页测试', '后台管理页测试', 'manage_options', 'submenu-page', 'submenu_page_callback' );
}
function submenu_page_callback() {
    echo '<div class="wrap">';
    echo '<h2>后台管理页测试</h2>';
    echo '</div>';
}
// 临时测试菜单
