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
        $request->setNick(var_get('nickname', 'wyattfang'));
        $request->setOutCode('blog');

        try {
            $result = $api->convertItems($request);

            $pic_width = (int)taobaoke_show_width() * 0.32;
            $pic_height = $pic_width;

            if (null != $result) {
                foreach ($result['taobaoke_items']['taobaoke_item'] as $item) {
                    $html = parse_string($html,
                        taobaoke_show_color('bg'), taobaoke_show_width(), taobaoke_show_color('border'),
                        $pic_width, $pic_height, $pic_width, $pic_height,
                        $item['iid'], $item['click_url'],
                        $pic_width, $pic_height, $pic_width, $pic_height,
                        $item['pic_url'], $item['iid'], $item['click_url'],
                        taobaoke_show_color('title'), $item['title'],
                        taobaoke_show_color('price'), $item['price'], $item['iid'], $item['click_url']);
                }
            }
            else {
                $html = parse_string($html,
                    taobaoke_show_color('bg'), taobaoke_show_width(), taobaoke_show_color('border'),
                    $pic_width, $pic_height, $pic_width, $pic_height,
                    $item['iid'], $item_url,
                    $pic_width, $pic_height, $pic_width, $pic_height,
                    $item_pic, $item['iid'], $item_url,
                    taobaoke_show_color('title'), $item_title,
                    taobaoke_show_color('price'), $item_price, $item['iid'], $item_url);
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

function taobaoke_sub_action_cart() {
    global $wpdb;

    $site_url = get_bloginfo('wpurl');
    $item_id = empty($_GET['item_id']) ? '-' : $_GET['item_id'];
    $item_title = empty($_GET['item_title']) ? '-' : urldecode($_GET['item_title']);
    $item_pic = empty($_GET['item_pic']) ? '#' : urldecode($_GET['item_pic']);
    $item_url = empty($_GET['click_url']) ? '#' : urldecode($_GET['click_url']);

    $user = wp_get_current_user();
    $user_id = $user->id;

    taobaoke_anaylysis(array('type' => 'promote_shop', 'site_url' => $site_url, 'item_id' => $item_id, 'item_name' => $item_title));

    //判断当前的商品是不是在收藏夹里面
    $cart_table_name = $wpdb->prefix . TAOBAOKE_CART_TABLE;
    $result = true;

    if ($wpdb->get_var("SELECT item_id FROM $cart_table_name WHERE `item_id` = '$item_id' and `user_id` = $user_id") > 0) {
        //do updating
        //$sql =  "UPDATE $cart_table_name SET `item_id = '$item_id', `item_title` = '$item_title', `item_pic` = '$item_pic', `update_time` = NOW() WHERE `user_id` = $user_id and `item_id` = '$item_id'";
        $result = $wpdb->query(
            $wpdb->prepare("UPDATE $cart_table_name SET `item_id` = %s, `item_title` = %s, `item_pic` = %s, `item_price` = %s, `item_url` = %s, `update_time` = NOW() WHERE `user_id` = %d and `item_id` = %s",
                           $item_id, $item_title, $item_pic, 0, $item_url, $user_id, $item_id)
            );
    }
    else {
        //do inserting
        //$sql = "INSERT INTO $cart_table_name VALUES ($user_id, '$item_id', '$item_title', '$item_pic', NOW(), NOW());";
        $result = $wpdb->query(
                $wpdb->prepare("INSERT INTO $cart_table_name VALUES (%d, %s, %s, %s, %s, %s, NOW(), NOW());", $user_id, $item_id, $item_title, $item_pic, 0, $item_url)
            );
    }

    return $result;
}

function taobaoke_sub_action_promote() {
    global $wpdb;

    $site_url = get_bloginfo('wpurl');
    $item_id = empty($_GET['item_id']) ? '-' : $_GET['item_id'];
    $item_title = empty($_GET['item_title']) ? '-' : urldecode($_GET['item_title']);
    $item_pic = empty($_GET['item_pic']) ? '#' : urldecode($_GET['item_pic']);
    $item_url = empty($_GET['click_url']) ? '#' : urldecode($_GET['click_url']);
    $shop_owner = empty($_GET['shop_owner']) ? '-' : $_GET['shop_owner'];

    $user = wp_get_current_user();
    $user_id = $user->id;

    taobaoke_anaylysis(array('type' => 'promote_shop', 'site_url' => $site_url, 'item_id' => $item_id, 'item_name' => $item_title));

    $promote_table_name = $wpdb->prefix . TAOBAOKE_PROMOTE_TABLE;
    $promote_type = 'sidebar';

    //prepare the output html to show in the sidebar
    include taobaoke_tpl_path() . 'html.tpl.php';

    $type = var_get('widget_display_type', 'pic');//pic or text
    $html = '';
    if ('pic' == $type) {
        $html = taobaoke_get_shop_sidebar_promote();
        $width = taobaoke_show_width();
        $pic_width = ($width * 0.32) . '';
        $html = parse_string($html,
            taobaoke_show_color('bg'), $width, taobaoke_show_color('border'),
            $pic_width, $pic_width, $item_url, $pic_width, $pic_width,
            $pic_width, $pic_width, $item_pic,
            $shop_owner, $item_url, ($width * 0.68) . '', taobaoke_show_color('title'), $item_title);
    }
    else {
        $html = taobaoke_get_shop_sidebar_promote_text();
        $html = parse_string($html,
            $shop_owner, $item_url, $width, taobaoke_show_color('title'), $item_title);
    }

    //$var['taobaoke_message'] = '添加商品推广成功！';
    if ($wpdb->get_var(
        $wpdb->prepare(
        "SELECT count(*) as promote_count FROM $promote_table_name WHERE `user_id` = $user_id and `item_id` = %s and `promote_type` = '$promote_type';"
            , $item_id)) > 0) {
        $reqult = $wpdb->query(
            $wpdb->prepare(
                "UPDATE $promote_table_name set `user_id` = $user_id, `item_id` = %s, `item_title` = %s, `item_pic` = %s, `item_price` = %s, `item_url` = %s, `item_html` = %s, `promote_type` = '{$promote_type}', `update_time` = NOW()",
                $item_id, $item_title, $item_pic, 0, $item_url, $html
                )
            );
        $var['taobaoke_message'] = '更新商品推广信息成功！';
    }
    else {
        $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO $promote_table_name VALUES($user_id, %s, %s, %s, %s, %s, %s, '{$promote_type}', NOW(), NOW());",
                $item_id, $item_title, $item_pic, 0, $item_url, $html
                )
            );
        $var['taobaoke_message'] = '新增加商品推广信息成功！';
    }
    return true;
}

function taobaoke_action_shop() {
    $var = array();
    $var['action_tpl'] = 'taobaoke-shop.tpl.php';

    $shop_owner = empty($_GET['shop_owner']) ? null : $_GET['shop_owner'];
    if (null == $shop_owner) {
        $var['message'] = '店铺昵称信息错误!';

        return $var;
    }

    if (!empty($_GET['sub_action'])) {//do actions
        $sub_action = $_GET['sub_action'];
        if ('cart' == $sub_action) {
            if (taobaoke_sub_action_cart()) {
                $var['message'] = '添加店铺到推广列表成功！';
            }
            else {
                $var['message'] = '添加店铺到推广列表失败！';
            }
        }
        else if ('promote' == $sub_action) {
            if (taobaoke_sub_action_promote()) {
                $var['message'] = '推广店铺成功！';
            }
            else {
                $var['message'] = '推广店铺失败！';
            }
        }
    }

    $taobaoke_api = new TaobaokeApi();
    $shop_request = new TaobaokeShopGetRequest();
    $shop_request->setFields();
    $shop_request->setNick($shop_owner);
    $shop_result = $taobaoke_api->getShop($shop_request);
    if (null != $shop_result) {
        if (array_key_exists('shop', $shop_result)) {
            $shop = $shop_result['shop'];

            $shop_convert_request = new TaobaokeShopConvertRequest();
            $shop_convert_request->setFields();
            $shop_convert_request->set0uterCode('blog');
            $shop_convert_request->setNick($shop['nick']);
            $shop_convert_request->setSids($shop['sid']);

            $shop_convert_result = $taobaoke_api->convertShop($shop_convert_request);
            if (null != $shop_convert_result && array_key_exists('taobaoke_shops', $shop_convert_result) && count($shop_convert_result['taobaoke_shops']['taobaoke_shop']) > 0) {
                $converted_shop = $shop_convert_result['taobaoke_shops']['taobaoke_shop'][0];

                $var['shop'] = $shop;
                $var['converted_shop'] = $converted_shop;

                $item_title = urlencode($converted_shop['shop_title']);
                $pic_url = urlencode('http://logo.taobao.com/shop-logo/' . $shop['pic_path']);
                $click_url = urlencode($converted_shop['click_url']);
                $var['shop_fav_url'] = buildUrl(array('sub_action'=>'cart', 'item_id'=>$shop['sid'], 'item_title'=>$item_title, 'item_pic'=>$pic_url, 'click_url'=>$click_url));
                $var['shop_promote_url'] = buildUrl(array('sub_action'=>'promote', 'item_id'=>$shop['sid'], 'item_title'=>$item_title, 'item_pic'=>$pic_url, 'click_url'=>$click_url));
            }
            else {
                $var['message'] = '当前店铺没有开通推广功能,推广后您没有收入, 所以还是不要推广他吧.';
            }
        }
    }
    else {
        $var['message'] = '当前店铺没有开通推广功能,推广后您没有收入, 所以还是不要推广他吧.';

        return $var;
    }

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
