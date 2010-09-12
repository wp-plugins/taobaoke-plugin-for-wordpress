<?php
/*
Plugin Name: Taobaoke Plugin For Wordpress
Plugin URI: http://tao.da-fang.com/
Description: 淘宝客的wordpress的插件，可以通过wordpress的后台添加淘宝客的商品或者店铺到您的blog来赚钱。Bug请提交到http://tao.da-fang.com
Version: 2.3.1
Author: Wyatt Fang
Author URI: http://blog.da-fang.com/
*/
$pathinfo = pathinfo(dirname(__FILE__));
define('TAOBAOKE_PLUGIN_FOLDER', $pathinfo['basename']);

include_once ('include.php');
wp_enqueue_script('thickbox');
wp_enqueue_style('thickbox');

if (function_exists('get_currentuserinfo')) {
    get_currentuserinfo();
}

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
    $auto_activity = var_get('auto-activity-ad', 0);
    $auto_hot = var_get('auto-hot-products', 0);
    $pid = var_get('pid');

    if ($auto_activity) {
        $activities = get_activities_from_db();
        if (isset($activities['totalCount']) && $activities['totalCount'] > 0) {
            $total = $activities['totalCount'];
            $rand_ad_index = array_rand($activities['promotions'], 1);

            $ad = $activities['promotions'][$rand_ad_index];

            $ad_url = str_replace('$pid', $pid, $ad['targetURL']);
            $ad_pic_url = $ad['picURL'];
            $activity_html = "<a href='$ad_url' target='_blank' ><img class='alignleft size-thumbnail' src='$ad_pic_url' style='width:200px;height:200px;' /></a>";

            $data = $activity_html . $data;
        }
    }

    if ($auto_hot) {
        $hot_keywords = get_hot_keyword_from_db(TAOBAOKE_HOT_KEYWORDS);
        if (null != $hot_keywords && is_array($hot_keywords)) { 

            $rand_hots = count($hot_keywords) > 8 ? array_rand($hot_keywords, 8) : array_rand($hot_keywords, count($hot_keywords));
            
            foreach ($rand_hots as $k_index) {
                $keyword = $k_index;
                $click_url = $hot_keywords[$keyword];
                $img_path = taobaoke_img_path();
                $ad_html .= "<a class='taobaoke-status-tracking-by-gotall-net $keyword' href='$click_url' target='_blank'>$keyword</a>" . '&nbsp;&nbsp;&nbsp;&nbsp;';
            }

            $data = $data . '<br /><br />' . $ad_html;
        }
    }

    return $data;
}

add_filter('the_content', 'taobaoke_add_random_ads');
add_filter('the_content_rss', 'taobaoke_add_random_ads');
add_filter('the_excerpt', 'taobaoke_add_random_ads');
add_filter('the_excerpt_rss', 'taobaoke_add_random_ads');

add_action("publish_post", "taobaoke_auto_add_keywords");
add_action("publish_page", "taobaoke_auto_add_keywords");
add_action("xmlrpc_publish_post", "taobaoke_auto_add_keywords");

function taobaoke_auto_add_keywords($id) {
    /*
    global $wpdb;
    $row = $wpdb->get_row("SELECT post_content FROM $wpdb->posts WHERE id=$id", ARRAY_A);

    $post =  $row["post_content"];
    
    $auto_keywords = get_hot_keyword_from_db(TAOBAOKE_AUTO_KEYWORDS);
    $search = array();
    $replacement = array();
    if (is_array($auto_keywords) && count($auto_keywords) > 0) {
        foreach ($auto_keywords as $keyword => $click_url) {
            $search[] = $keyword;
            $replacement[] = "<a href='$click_url' target='_blank'>$keyword</a>";
        }

        $new_post = str_replace($search, $replacement, $post);
        $new_post = addslashes($new_post);
        log_message("UPDATE TABLE $wpdb->posts SET `post_content` = '$new_post' WHERE id = $id;");

        $wpdb->query("UPDATE TABLE $wpdb->posts SET `post_content` = '$new_post' WHERE id = $id;");
    }*/
}

function taobaoke_auto_sync_callback() {
    sync_hot_keywords();
    auto_sync_activities();
}

$v = get_option('taobaoke_db_version', '0.1');
if ($v != TAOBAOKE_DB_V) {
    include_once(TAO_PATH . 'php/activation.php');

    taobaoke_install_db(); //upgrade db
    taobaoke_auto_sync_callback();
}

// send automatic scheduled auto sync
if (!wp_next_scheduled('taobaoke_auto_sync') ) {
	wp_schedule_event(time(), 'daily', 'taobaoke_auto_sync' ); // hourly, daily and twicedaily
}

add_action('taobaoke_auto_sync','taobaoke_auto_sync_callback');
?>
