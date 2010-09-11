<?php
function taobaoke_get_setting($setting_name) {

    $default = null;
    if ('appkey' == $setting_name) {
        $default = APP_KEY;
    }
    else if ('appsecret' == $setting_name) {
        $default = APP_SECRET;
    }
    else if ('pid' == $setting_name) {
        $default = TOP_PID;
    }
    else if ('nickname' == $setting_name) {
        $default = TOP_NICK;
    }

    return var_get($setting_name, $default);
}

function display_page() {
    $vars = array();

    if (!empty($_POST['Taobaoke-Submit'])) {
        $pid = trim($_POST['pid']);
        $appkey = trim($_POST['appkey']);
        $appsecret = trim($_POST['appsecret']);
        $nickname = trim($_POST['nickname']);

        if (is_not_empty($pid) && is_not_empty($appkey) && is_not_empty($appsecret)) {
            //save the setting into database
            var_set('pid', $pid);
            var_set('appkey', $appkey);
            var_set('appsecret', $appsecret);

            $vars['taobaoke_message'] = '更新成功';
        }
        else {
            $vars['taobaoke_message'] = 'pid，appkey, appsecret 都不能为空！';
        }

        if (is_not_empty($nickname)) {
            var_set('nickname', $nickname);
        }
    }

    if (!empty($_POST['Taobaoke-Auto-Ad'])) {
        $auto_activity_ad = isset($_POST['taobaoke-auto-activity-ad']) ? $_POST['taobaoke-auto-activity-ad'] : 0;
        $auto_product_ad = isset($_POST['taobaoke-auto-product-ad']) ? $_POST['taobaoke-auto-product-ad'] : 0;
        $auto_hot_product_ad = isset($_POST['taobaoke-auto-hot-products']) ? $_POST['taobaoke-auto-hot-products'] : 0;
        $sidebar_count = trim($_POST['taobaoke-sidebar-ads-count']);

        var_set('auto-activity-ad', $auto_activity_ad);
        var_set('auto-product-ad', $auto_product_ad);
        var_set('auto-hot-products', $auto_hot_product_ad);
        var_set('sidebar-display-count', $sidebar_count);
    }

    if (!empty($_POST['Taobaoke-Auto-Keywords'])) {
        $keywords = trim($_POST['taobaoke-keywords']);

        var_set('auto-keywords', $keywords);
    }

    $vars['taobaoke_pid'] = taobaoke_get_setting('pid');
    $vars['taobaoke_appkey'] = taobaoke_get_setting('appkey');
    $vars['taobaoke_appsecret'] = taobaoke_get_setting('appsecret');
    $vars['taobaoke_nickname'] = taobaoke_get_setting('nickname');

    $vars['taobaoke_auto_activity'] = (1 == var_get('auto-activity-ad', 0)) ? true : false;
    $vars['taobaoke_auto_product'] = (1 == var_get('auto-product-ad', 0)) ? true : false;
    $vars['taobaoke_auto_hot_products'] = (1 == var_get('auto-hot-products', 0)) ? true : false;


    $vars['taobaoke_keywords'] = var_get('auto-keywords');
    $vars['taobaoke_sidebar_ad_count'] = var_get('sidebar-display-count', 5);

    return $vars;
}
?>
