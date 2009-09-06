<?php
function display_page() {
    $action = empty($_GET['action']) ? null : $_GET['action'];
    if (null !== $action) {
        return call_user_func('taobaoke_action_' . $action);
    }
}

function taobaoke_action_cart() {
    global $wpdb;

    $var = array();

    $var['action_tpl'] = 'taobaoke-cart.tpl.php';

    if (!taobaoke_action_cart_validation()) {
        $var['error'] = '格式不正确，请重新选择';

        return $var;
    }

    $item_id = $_GET['item_id'];
    $var['item_id'] = $item_id;

    $item_title = $_GET['item_title'];
    $var['item_title'] = $item_title;

    $item_pic = $_GET['item_pic'];
    $var['item_pic'] = $item_pic;

    $item_price = $_GET['price'];

    $item_url = $_GET['item_url'];
    $var['item_url'] = $item_url;

    $user = wp_get_current_user();
    $user_id = $user->id;

    $cid = empty($_GET['cid']) ? '0' : $_GET['cid'];
    $name = empty($_GET['name']) ? 'no-name' : $_GET['name'];
    $site_url = get_bloginfo('wpurl');
    taobaoke_anaylysis(array('type' => 'promote', 'site_url' => $site_url, 'item_id' => $item_id, 'item_name' => $item_title, 'item_cat_id' => $cid, 'item_cat_name' => $name));

    //判断当前的商品是不是在收藏夹里面
    $cart_table_name = $wpdb->prefix . TAOBAOKE_CART_TABLE;
    if ($wpdb->get_var("SELECT item_id FROM $cart_table_name WHERE `item_id` = '$item_id' and `user_id` = $user_id") > 0) {
        //do updating
        //$sql =  "UPDATE $cart_table_name SET `item_id = '$item_id', `item_title` = '$item_title', `item_pic` = '$item_pic', `update_time` = NOW() WHERE `user_id` = $user_id and `item_id` = '$item_id'";
        $wpdb->query(
            $wpdb->prepare("UPDATE $cart_table_name SET `item_id` = %s, `item_title` = %s, `item_pic` = %s, `item_price` = %s, `item_url` = %s, `update_time` = NOW() WHERE `user_id` = %d and `item_id` = %s",
                           $item_id, $item_title, $item_pic, $item_price, $item_url, $user_id, $item_id)
            );
    }
    else {
        //do inserting
        //$sql = "INSERT INTO $cart_table_name VALUES ($user_id, '$item_id', '$item_title', '$item_pic', NOW(), NOW());";
        $wpdb->query(
                $wpdb->prepare("INSERT INTO $cart_table_name VALUES (%d, %s, %s, %s, %s, %s, NOW(), NOW());", $user_id, $item_id, $item_title, $item_pic, $item_price, $item_url)
            );
    }
    $var['message'] = '添加淘宝推广商品到推广列表成功！';

    return $var;
}

function taobaoke_action_promote() {
    global $wpdb;

    $var = array();

    $var['action_tpl'] = 'taobaoke-promote.tpl.php';

    if (!taobaoke_action_cart_validation()) {
        $var['error'] = '格式不正确，请重新选择';

        return $var;
    }

    $item_id = $_GET['item_id'];
    $var['item_id'] = $item_id;

    $item_title = $_GET['item_title'];
    $var['item_title'] = $item_title;

    $item_pic = $_GET['item_pic'];
    $var['item_pic'] = $item_pic;

    $item_price = $_GET['price'];

    $item_url = $_GET['item_url'];
    $var['item_url'] = $item_url;

    $item_url .= '&p=' . var_get('pid');//add the pid info

    $user = wp_get_current_user();
    $user_id = $user->id;

    $promote_type = 'sidebar';

    if (!empty($_POST['taobaoke_submit_type'])) {
        $cid = empty($_GET['cid']) ? '0' : $_GET['cid'];
        $name = empty($_GET['name']) ? 'no-name' : $_GET['name'];
        $site_url = get_bloginfo('wpurl');
        taobaoke_anaylysis(array('type' => 'promote', 'site_url' => $site_url, 'item_id' => $item_id, 'item_name' => $item_title, 'item_cat_id' => $cid, 'item_cat_name' => $name));

        //prepare the output html to show in the sidebar
        include taobaoke_tpl_path() . 'html.tpl.php';
        $html = taobaoke_get_ad_html();

        $api = new TaobaokeApi();

        $request = new TaobaokeItemsConvertRequest();
        $request->setFields();
        $request->setIids($item_id);
        $request->setNick('wyattfang');//TODO
        $request->setOutCode('blog');

        try {
            $result = $api->convertItems($request);
            if (null != $result) {
                foreach ($result['taobaokeItems'] as $item) {
                    $html = parse_string($html,
                        taobaoke_show_color('bg'), taobaoke_show_width(), taobaoke_show_color('border'),
                        $item['id'], $item['click_url'], $item['pict_url'], $item['id'], $item['click_url'], $item['title'],
                        taobaoke_show_color('price'), $item['price'], $item['id'], $item['click_url']);
                }
            }
            else {
                $html = parse_string($html,
                    taobaoke_show_color('bg'), taobaoke_show_width(), taobaoke_show_color('border'),
                    $item['id'], $item_url, $item_pic, $item['id'], $item_url, $item_title,
                    taobaoke_show_color('price'), $item_price, $item['id'], $item_url);
            }
        }
        catch (Exception $e) {
            //TODO
        }

        $promote_table_name = $wpdb->prefix . TAOBAOKE_PROMOTE_TABLE;

        //$var['taobaoke_message'] = '添加商品推广成功！';
        if ($wpdb->get_var(
            $wpdb->prepare(
            "SELECT count(*) as promote_count FROM $promote_table_name WHERE `user_id` = $user_id and `item_id` = %s and `promote_type` = '$promote_type';"
                , $item_id)) > 0) {
            $reqult = $wpdb->query(
                $wpdb->prepare(
                    "UPDATE $promote_table_name set `user_id` = $user_id, `item_id` = %s, `item_title` = %s, `item_pic` = %s, `item_price` = %s, `item_url` = %s, `item_html` = %s, `promote_type` = '{$promote_type}', `update_time` = NOW()",
                    $item_id, $item_title, $item_pic, $item_price, $item_url, $html
                    )
                );
            $var['taobaoke_message'] = '更新商品推广信息成功！';
        }
        else {
            $wpdb->query(
                $wpdb->prepare(
                    "INSERT INTO $promote_table_name VALUES($user_id, %s, %s, %s, %s, %s, %s, '{$promote_type}', NOW(), NOW());",
                    $item_id, $item_title, $item_pic, $item_price, $item_url, $html
                    )
                );
            $var['taobaoke_message'] = '新增加商品推广信息成功！';
        }
    }

    $var['item_count'] = taobaoke_get_promote_count($user_id, $promote_type);

    return $var;
}

function taobaoke_get_promote_count($user_id, $promote_type) {
    global $wpdb;

    $promote_table_name = $wpdb->prefix . TAOBAOKE_PROMOTE_TABLE;

    return $wpdb->get_var(
        "SELECT count(*) as promote_count FROM $promote_table_name WHERE `user_id` = $user_id;"
        );
}

function taobaoke_action_cart_validation() {
    if (empty($_GET['item_id']) || empty($_GET['item_title']) || empty($_GET['item_pic'])) {
        return false;
    }

    return true;
}
?>
