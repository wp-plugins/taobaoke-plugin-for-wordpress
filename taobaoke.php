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

function taobaoke_add_random_ads($data) {
    if (var_get('auto-activity-ad', 0) || var_get('auto-product-ad', 0) || var_get('auto-hot-products', 0)) {
        $random = rand(1, 5);
        $pid = var_get('pid');

        if (1 == $random || 2 == $random) {
            $hot_keywords = get_hot_keywords();
            if (isset($hot_keywords['totalCount']) && $hot_keywords['totalCount'] > 0) {
                $keywords = array_rand($hot_keywords['hotkeywords'], 5);
                
                $ad_html = '';
                foreach ($keywords as $keyword_index) {
                    $keyword = $hot_keywords['hotkeywords'][$keyword_index];
                    $k = rawurlencode($keyword);

                    $ad_html .= "<a href='http://search8.taobao.com/browse/search_auction.htm?q=$k&cat=0&pid=$pid&viewIndex=7' target='_blank'>$keyword</a>" . '&nbsp;&nbsp;&nbsp;&nbsp;';
                }

                return $data . '<br />' . $ad_html;
            }
        }
        else {
            $activities = get_activities();
            if (isset($activities['totalCount']) && $activities['totalCount'] > 0) {
                $total = $activities['totalCount'];
                $rand_ad_index = array_rand($activities['promotions'], 1);

                $ad = $activities['promotions'][$rand_ad_index];

                $ad_url = str_replace('$pid', $pid, $ad['targetURL']);
                $ad_pic_url = $ad['picURL'];
                $ad_html = "<a href='$ad_url' target='_blank' ><img class='aligncenter' src='$ad_pic_url' style='width:200px;height:200px;' /></a>";

                return $data . '<br />' . $ad_html;
            }
        }
    }
    return $data;
}

add_filter('the_content', 'taobaoke_add_random_ads');
add_filter('the_content_rss', 'taobaoke_add_random_ads');
add_filter('the_excerpt', 'taobaoke_add_random_ads');
add_filter('the_excerpt_rss', 'taobaoke_add_random_ads');
?>
