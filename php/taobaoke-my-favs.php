<?php
class MyFavItems {
    public function getColumns() {
        return array (
            'item_title' => array(
                'header' => '商品名',
                'sortable' => true,
                'cell_style' => 'text-align:left;width:30%',
            ),
            'item_pic' => array(
                'header' => '图片',
                'sortable' => false,
                'function' => 'showImage'
            ),
            'action' => array(
                'header' => '操作',
                'function' => 'showActions',
                'sortable' => false
            ),
        );
    }

    public function getDataSource() {
        global $wpdb;
        $table = $wpdb->prefix . TAOBAOKE_CART_TABLE;
        $user = wp_get_current_user();
        $user_id = $user->id;

        return new DatabaseDataSource("SELECT * FROM {$table} WHERE `user_id` = $user_id", array('item_title' => 'ASC'), array(0, 10));
    }

    public function showImage($item_pic, $row) {
        return "<a class=\"thickbox\" rel=\"淘宝图片\" href=\"{$row['item_pic']}\"><img src=\"{$row['item_pic']}\" style=\"width:72px;height:80px\" /></a>";
    }

    public function showActions($null, $row) {
        $url = buildUrl(array('action' => 'delete_fav', 'item_id' => $row['item_id']));
        return "<a href=\"{$url}\">删除</a>";
    }
}

function display_page() {
    $vars = array();

    if (!empty($_GET['action'])) {
        global $wpdb;
        if ('delete_fav' == $_GET['action']) {
            $item_id = $_GET['item_id'];
            $table = $wpdb->prefix . TAOBAOKE_CART_TABLE;
            $wpdb->query("DELETE FROM {$table} WHERE `item_id` = '$item_id'");
        }
        else if ('autoclean' == $_GET['action']) {
            $table = $wpdb->prefix . TAOBAOKE_CART_TABLE;
            $result = $wpdb->get_results("SELECT `item_id` FROM $table");

            $api = new TaobaokeApi();
            $request = new TaobaokeItemDetailGetRequest();
            $request->setFields();
            $request->setNick(var_get('nickname'));
            $request->setOuterCode();

            $item_ids = array();
            $index = 0;
            foreach ($result as $cur) {
                $item_ids[] = $cur->item_id;

                $index++;
                if (10 == $index) {
                    $iids = implode(',', $item_ids);

                    $request->setIids($iids);

                    $api_result = $api->getItemsDetail($request);
                    if ($api_result['total_results'] > 0) {
                        foreach ($api_result['taobaoke_item_details']['taobaoke_item_detail'] as $item_detail) {
                            $delist_time = $item_detail['item']['delist_time'];
                            $num_iid = $item_detail['item']['num_iid'];

                            $delist_timestamp = strtotime($delist_time);
                            if ($delist_timestamp < time()) {
                                $wpdb->query("DELETE FROM $table WHERE `item_id` = '$num_iid'");
                            }
                        }

                        $index = 0;
                        $item_ids = array();
                    }
                }
            }
            
            if (count($item_ids) > 0) {
                $iids = implode(',', $item_ids);

                $request->setIids($iids);

                $api_result = $api->getItemsDetail($request);

                if ($api_result['total_results'] > 0) {
                    foreach ($api_result['taobaoke_item_details']['taobaoke_item_detail'] as $item_detail) {
                        $delist_time = $item_detail['item']['delist_time'];
                        $num_iid = $item_detail['item']['num_iid'];

                        $delist_timestamp = strtotime($delist_time);
                        if ($delist_timestamp < time()) {
                            $wpdb->query("DELETE FROM $table WHERE `item_id` = '$num_iid'");
                        }
                    }
                }
            }
        }
    }

    $fav_controller = new MyFavItems();
    $fav_table = new Table($fav_controller, $fav_controller->getColumns(), $fav_controller->getDataSource());
    $vars['fav_table'] = $fav_table;

    return $vars;
}
?>
