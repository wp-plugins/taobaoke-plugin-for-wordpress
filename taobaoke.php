<?php
/*
Plugin Name: Taobaoke Plugin For Wordpress
Plugin URI: http://blog.gotall.net/
Description: 淘宝客的wordpress的插件，可以通过wordpress的后台添加淘宝客的商品到您的blog来赚钱。Bug请提交到http://blog.gotall.net
Version: 1.2
Author: Wyatt Fang
Author URI: http://blog.gotall.net/
*/

include_once ('include.php');
wp_enqueue_script('thickbox');
wp_enqueue_style('thickbox');

$current_page = $_SERVER["PHP_SELF"];

if (false !== strpos($current_page, 'plugins.php')) {
    include_once(TAO_PATH . 'php/activation.php');

    register_activation_hook(__FILE__, 'taobaoke_activate_plugin');
    register_deactivation_hook(__FILE__, 'taobaoke_deactivate_plugin');
}

if (ereg('/wp-admin/', $_SERVER['REQUEST_URI'])) { // just load in admin
    include_once TAO_PATH .'php/menu-hook.php';

    add_action('admin_menu', 'taobaoke_option_menu');

    require TAO_PATH . 'php/post-hook.php';

    // WP >= 2.5
	add_action('media_buttons', 'taobaoke_media_buttons');
	add_filter('media_buttons_context', 'taobaoke_media_buttons_context');
    add_action('media_upload_taobaoke_list_fav', 'taobaoke_list_fav');
}

include_once(TAO_PATH . 'php/widgets.php');
add_action('wp_head', 'taobaoke_gotall_analytics_vars');
add_action('wp_footer', 'taobaoke_gotall_analytics');
?>
