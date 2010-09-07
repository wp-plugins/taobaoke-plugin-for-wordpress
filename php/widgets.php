<?php
function taobaoke_show_display_type($type) {
    $display_type = var_get('widget_display_type', 'pic');

    if ($type == $display_type) {
        echo 'checked';
    }
    else {
        echo '';
    }
}

function taobaoke_show_which_item($item_type) {
   $show_or_not = var_get('widget_show_item', 'all');
   if ($show_or_not == $item_type) {
      echo ' checked ';
   }
   else {
        echo '  ';
   }
}

function taobaoke_show_display_style($style) {
    $checked = var_get('widget_display_style', 'left-right');
    if ($checked == $style) {
        echo 'checked';
    }
    else {
        echo '';
    }
}

function taobaoke_widget_sidebar()  {
    $widget_title = var_get('widget_title');
    if (empty($widget_title)) {
        $widget_title = '淘宝客 - 侧边栏推荐';
    }

    $before_widget = ''; //自己根据自己主题的格式更改样式
    $before_title = '<h1>';//自己根据自己主题的格式更改样式
    $after_title = '</h1>';//自己根据自己主题的格式更改样式
    $after_widget = '';//自己根据自己主题的格式更改样式

    $vars['before_widget'] = $before_widget;
    $vars['before_title'] = $before_title;
    $vars['after_title'] = $after_title;
    $vars['after_widget'] = $after_widget;

    taobaoke_widget_sidebar_promote($vars);
}

function taobaoke_widget_sidebar_promote($args) {
    global $wpdb;

    extract($args);

    $widget_title = var_get('widget_title');
    if (empty($widget_title)) {
        $widget_title = '淘宝客 - 侧边栏推荐';
    }

    echo $before_widget;
    echo $before_title . $widget_title . $after_title;

    $table_name = $wpdb->prefix . TAOBAOKE_PROMOTE_TABLE;

    $content = '';

    $promote_item_total = $wpdb->get_var("SELECT COUNT(*) AS count FROM $table_name WHERE `promote_type` = 'sidebar';");

    if ($promote_item_total > 0) {
        $promote_count = 5;//to be put into configuration page

        $min = 0;
        if ($promote_item_total > 5) {//TODO: Hard code 5 here, will put it in the configuration file later
            $range = $promote_item_total - 5;
            $min = rand(0, $range);
        }

        $result = $wpdb->get_results("SELECT `item_id`, `item_html` FROM $table_name WHERE `promote_type` = 'sidebar' LIMIT $min, 5;");

        if ($result) {

            foreach ($result as $cur) {
                $content .= $cur->item_html . '<br />';
            }

            $content .= "<table width=95%><tr><td align='right'>Powered by <a href='http://blog.da-fang.com/index.php/%E6%B7%98%E5%AE%9D%E5%AE%A2/' target='_blank'>淘宝客</a></td></tr></table>";
        }
    }
    else {
        $content = '暂无推广信息';
    }

    echo $content;
    echo $after_widget;
}

function taobaoke_widget_register() {
    register_sidebar_widget('Taobaoke-Widget', 'taobaoke_widget_sidebar_promote');
}

function taobaoke_save_info_to_db() {
    include_once taobaoke_tpl_path() . 'html.tpl.php';

    global $wpdb;
    $user = wp_get_current_user();
    $user_id = $user->id;

    $table_name = $wpdb->prefix . TAOBAOKE_PROMOTE_TABLE;

    $result_in_db = $wpdb->get_results("SELECT `item_id`, `item_title`, `item_pic`, `item_price`, `item_url` FROM $table_name WHERE `user_id` = $user_id AND `promote_type` = 'sidebar';");
    $iids = '';
    foreach ($result_in_db as $cur) {
        $iids .= $cur->item_id . ',';
    }

    $converted_success = array();

    $result = null;

    if ('' != $iids) {
        $iids = substr($iids, 0, -1);
        $api = new TaobaokeApi();

        $request = new TaobaokeItemsConvertRequest();
        $request->setFields();
        $request->setIids($iids);
        $request->setNick(var_get('nickname', 'wyattfang'));
        $request->setOutCode('blog');

        try {
            $result = $api->convertItems($request);
        }
        catch (Exception $ex) {
            //TODO
        }
    }

    $taobaoke_promote_format = !array_key_exists('taobaoke_promote_format', $_POST) ? 1 : $_POST['taobaoke_promote_format'];
    if ($taobaoke_promote_format) {
        var_set('widget_display_type', 'pic');
    }
    else {
        var_set('widget_display_type', 'text');

        $html = taobaoke_get_ad_raw_text();

        if (null != $result) {
            foreach ($result['taobaoke_items']['taobaoke_item'] as $item) {
                $converted_success[$item['iid']] = true;

                $html = parse_string($html, $item['iid'], $item['click_url'], $item['title']);

                $item_id = $item['iid'];
                $wpdb->query(
                    "UPDATE $table_name SET `item_html` = '$html' WHERE `user_id` = $user_id AND `item_id` = '$item_id';"
                );
            }
        }

        $html = taobaoke_get_ad_raw_text();
        foreach ($result_in_db as $item) {
            if (!array_key_exists($item->item_id, $converted_success)) {
                $html = parse_string($html, $item->item_id, $item->item_url, $item->item_title);

                $item_id = $item->item_id;
                $wpdb->query(
                    "UPDATE $table_name SET `item_html` = '$html' WHERE `user_id` = $user_id AND `item_id` = '$item_id';"
                );
            }
        }


        return;
    }

    $taobaoke_widget_display_format = !array_key_exists('taobaoke_widget_display_format', $_POST) ? 1 : $_POST['taobaoke_widget_display_format'];
    if ($taobaoke_widget_display_format) {
        var_set('widget_display_style', 'left-right');
    }
    else {
        var_set('widget_display_style', 'up-down');
    }

    $taobaoke_widget_show_item = !array_key_exists('taobaoke_widget_show_item', $_POST) ? 'all' : $_POST['taobaoke_widget_show_item'];
    var_set('widget_show_item', $taobaoke_widget_show_item);

    if (!empty($_POST['taobaoke_widget_show_detail_button'])) {
        var_set('widget_show_item_detail', 1);
    }
    else {
        var_set('widget_show_item_detail', 0);
    }
    if (!empty($_POST['taobaoke_widget_show_price'])) {
        var_set('widget_show_item_price', 1);
    }
    else {
        var_set('widget_show_item_price', 0);
    }
    if (!empty($_POST['taobaoke_widget_show_title'])) {
        var_set('widget_show_item_title', 1);
    }
    else {
        var_set('widget_show_item_title', 0);
    }

    $taobaoke_widget_height = empty($_POST['taobaoke_widget_height']) ? TAOBAOKE_SIDEBAR_HEIGHT : $_POST['taobaoke_widget_height'];
    $taobaoke_widget_width = empty($_POST['taobaoke_widget_width']) ? TAOBAOKE_SIDEBAR_WIDTH : $_POST['taobaoke_widget_width'];
    var_set('widget_width', $taobaoke_widget_width);
    var_set('widget_height', $taobaoke_widget_height);

    $taobaoke_widget_bg_color = empty($_POST['taobaoke_widget_bg_color']) ? TAOBAOKE_SIDEBAR_BG_COLOR : $_POST['taobaoke_widget_bg_color'];
    $taobaoke_widget_border_color = empty($_POST['taobaoke_widget_border_color']) ? TAOBAOKE_SIDEBAR_BORDER_COLOR : $_POST['taobaoke_widget_border_color'];
    $taobaoke_widget_price_color = empty($_POST['taobaoke_widget_price_color']) ? TAOBAOKE_SIDEBAR_PRICE_COLOR : $_POST['taobaoke_widget_price_color'];
    $taobaoke_widget_title_color = empty($_POST['taobaoke_widget_title_color']) ? TAOBAOKE_SIDEBAR_TITLE_COLOR : $_POST['taobaoke_widget_title_color'];
    var_set('widget_color_title', $taobaoke_widget_title_color);
    var_set('widget_color_bg', $taobaoke_widget_bg_color);
    var_set('widget_color_border', $taobaoke_widget_border_color);
    var_set('widget_color_price', $taobaoke_widget_price_color);

    if ($iids !== '') {
        if (null != $result) {
            foreach ($result['taobaoke_items']['taobaoke_item'] as $item) {
                $converted_success[$item['iid']] = true;

                $html = '';
                if ('pic' == $taobaoke_widget_show_item) {
                    $html = taobaoke_get_ad_pic();

                    $html = parse_string($html,
                        $taobaoke_widget_bg_color, $taobaoke_widget_width, $taobaoke_widget_border_color,
                        $taobaoke_widget_width - 2, $taobaoke_widget_height -2,
                        $item['iid'], $item['click_url'], $taobaoke_widget_width - 2, $taobaoke_widget_height -2,
                        $item['pic_url']);
                }
                else if ('pic-title' == $taobaoke_widget_show_item) {
                    $html = taobaoke_get_ad_html_pic_title();
                    $pic_width = (int)$taobaoke_widget_width * 0.32;
                    $pic_height = $pic_width;

                    $html = parse_string($html,
                        $taobaoke_widget_bg_color, $taobaoke_widget_width, $taobaoke_widget_border_color,
                        $pic_width, $pic_height, $pic_width, $pic_height,
                        $item['iid'], $item['click_url'],
                        $pic_width, $pic_height, $pic_width, $pic_height,
                        $item['pic_url'], $item['iid'], $item['click_url'],
                        $taobaoke_widget_title_color, $item['title']);
                }
                else {
                    $html = taobaoke_get_ad_html();

                    $pic_width = (int)$taobaoke_widget_width * 0.32;
                    $pic_height = $pic_width;

                    $html = parse_string($html,
                        $taobaoke_widget_bg_color, $taobaoke_widget_width, $taobaoke_widget_border_color,
                        $pic_width, $pic_height, $pic_width, $pic_height,
                        $item['iid'], $item['click_url'],
                        $pic_width, $pic_height, $pic_width, $pic_height,
                        $item['pic_url'], $item['iid'], $item['click_url'], $taobaoke_widget_title_color, $item['title'],
                        $taobaoke_widget_price_color, $item['price'], $item['iid'], $item['click_url']);
                }

                $item_id = $item['iid'];
                $wpdb->query(
                    "UPDATE $table_name SET `item_html` = '$html' WHERE `user_id` = $user_id AND `item_id` = '$item_id';"
                );
            }
        }

        foreach ($result_in_db as $item) {
            if (!array_key_exists($item->item_id, $converted_success)) {
                $html = '';
                $item->item_url .= '&p=' . var_get('pid');//add the pid info

                if ('pic' == $taobaoke_widget_show_item) {
                    $html = taobaoke_get_ad_pic();

                    $html = parse_string($html,
                        $taobaoke_widget_bg_color, $taobaoke_widget_width, $taobaoke_widget_border_color,
                        $taobaoke_widget_width - 2, $taobaoke_widget_height -2,
                        $item->item_url, $item->item_url, $taobaoke_widget_width - 2, $taobaoke_widget_height -2,
                        $taobaoke_widget_width - 2, $taobaoke_widget_height -2, $item->item_pic);
                }
                else if ('pic-title' == $taobaoke_widget_show_item) {
                    $html = taobaoke_get_ad_html_pic_title();

                    $pic_width = (int)$taobaoke_widget_width * 0.32;
                    $pic_height = $pic_width;

                    $html = parse_string($html,
                        $taobaoke_widget_bg_color, $taobaoke_widget_width, $taobaoke_widget_border_color,
                        $pic_width, $pic_height, $pic_width, $pic_height,
                        $item->item_id, $item->item_url,
                        $pic_width, $pic_height, $pic_width, $pic_height,
                        $item->item_pic, $item->item_id, $item->item_url,
                        $taobaoke_widget_title_color, $item->item_title);
                }
                else if (0 == $item->item_price || '0' == $item->item_price){ //shop promote
                    $html = taobaoke_get_shop_sidebar_promote();

                    $pic_width = (int)$taobaoke_widget_width * 0.32;
                    $pic_height = $pic_width;

                    $html = parse_string($html,
                        $taobaoke_widget_bg_color, $taobaoke_widget_width, $taobaoke_widget_border_color,
                        $pic_width, $pic_height, $item->item_url, $pic_width, $pic_height,
                        $pic_width, $pic_height, $item->item_pic,
                        $item->item_id, $item->item_url, ($taobaoke_widget_width * 0.68) . '', $taobaoke_widget_title_color, $item->item_title);
                }
                else {
                    $html = taobaoke_get_ad_html();

                    $pic_width = (int)$taobaoke_widget_width * 0.32;
                    $pic_height = $pic_width;

                    $html = parse_string($html,
                        $taobaoke_widget_bg_color, $taobaoke_widget_width, $taobaoke_widget_border_color,
                        $pic_width, $pic_height, $pic_width, $pic_height,
                        $item->item_id, $item->item_url,
                        $pic_width, $pic_height, $pic_width, $pic_height,
                        $item->item_pic, $item->item_id, $item->item_url, $taobaoke_widget_title_color, $item->item_title,
                        $taobaoke_widget_price_color, $item->item_price, $item->item_id, $item->item_url);
                }

                $item_id = $item->item_id;
                $wpdb->query(
                    "UPDATE $table_name SET `item_html` = '$html' WHERE `user_id` = $user_id AND `item_id` = '$item_id';"
                );
            }
        }

    }


}

function taobaoke_widget_promote_options() {
    $widget_title = var_get('widget_title');
    if (empty($widget_title)) {
        $widget_title = '淘宝客 - 侧边栏推荐';
    }

    if (!empty($_POST['taobaoke_widget_post'])) {
        //update db
        $widget_title = empty($_POST['taobaoke_widget_title']) ? '淘宝客 - 侧边栏推荐' : $_POST['taobaoke_widget_title'];
        var_set('widget_title', $widget_title);

        //TODO: 验证
        taobaoke_save_info_to_db();
    }

    include taobaoke_tpl_path() . 'widget.tpl.php';
}

function taobaoke_widget_admin_styles() {
    wp_register_script('taobaoke_widget_jquery_js', taobaoke_js_path() . 'colorpicker/js/jquery.js', null, '1.0');
    wp_enqueue_script('taobaoke_widget_jquery_js');

    wp_register_script('taobaoke_widget_colorpicker_js', taobaoke_js_path() . 'colorpicker/js/colorpicker.js', null, '1.0');
    wp_enqueue_script('taobaoke_widget_colorpicker_js');

    wp_register_style('taobaoke_widget_colorpicker_css', taobaoke_js_path() . 'colorpicker/css/colorpicker.css', null, '1.0');
    wp_enqueue_style('taobaoke_widget_colorpicker_css');
}

function taobaoke_gotall_analytics_vars() {
    echo "<!-- Gotall Analytics Tracking by Gotall Analyticator -->\n";

    echo "<script type=\"text/javascript\">\n\n";
    echo "var gotall_analytics_of_site = '" . get_bloginfo('wpurl') . "';\n\n";
    echo "</script>\n\n";

    echo "<!-- Gotall Analytics Tracking by Gotall Analyticator End-->\n";
}

function taobaoke_gotall_analytics() {
    echo "<!-- Gotall Analytics Tracking by Gotall Analyticator -->\n";

    $js_url = taobaoke_js_path() . 'external.jquery.js?version=1.7';

    echo "<script type=\"text/javascript\" src=\"{$js_url}\"></script>\n\n";
    echo "<!-- Gotall Analytics Tracking by Gotall Analyticator End-->\n";
}

add_action('plugins_loaded', taobaoke_widget_register);
register_widget_control('Taobaoke-Widget', 'taobaoke_widget_promote_options', 450, 500);

add_action('admin_print_styles-widgets.php', 'taobaoke_widget_admin_styles');
