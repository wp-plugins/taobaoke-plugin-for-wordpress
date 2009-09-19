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

    $vars['taobaoke_pid'] = taobaoke_get_setting('pid');
    $vars['taobaoke_appkey'] = taobaoke_get_setting('appkey');
    $vars['taobaoke_appsecret'] = taobaoke_get_setting('appsecret');
    $vars['taobaoke_nickname'] = taobaoke_get_setting('nickname');

    return $vars;
}
?>
