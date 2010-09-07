<?php
/*
Plugin Name: Taobaoke Plugin For Wordpress
Plugin URI: http://blog.da-fang.com/index.php/%E6%B7%98%E5%AE%9D%E5%AE%A2/
Description: 淘宝客的wordpress的插件，可以通过wordpress的后台添加淘宝客的商品或者店铺到您的blog来赚钱。Bug请提交到http://blog.da-fang.com
Version: 2.2.3
Author: Wyatt Fang
Author URI: http://blog.da-fang.com/
*/
$pathinfo = pathinfo(dirname(__FILE__));
define('TAOBAOKE_PLUGIN_FOLDER', $pathinfo['basename']);

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
	add_action('media_upload_taobaoke_list_search', 'taobaoke_list_search');
}

include_once(TAO_PATH . 'php/widgets.php');
add_action('wp_head', 'taobaoke_gotall_analytics_vars');
add_action('wp_footer', 'taobaoke_gotall_analytics');
?>
