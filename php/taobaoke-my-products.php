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

class MyPromoteItems {
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
        $table = $wpdb->prefix . TAOBAOKE_PROMOTE_TABLE;
        $user = wp_get_current_user();
        $user_id = $user->id;

        return new DatabaseDataSource("SELECT * FROM {$table} WHERE `user_id` = $user_id", array('item_title' => 'ASC'), array(0, 10));
    }

    public function showImage($item_pic, $row) {
        return "<a class=\"thickbox\" rel=\"淘宝图片\" href=\"{$row['item_pic']}\"><img src=\"{$row['item_pic']}\" style=\"width:72px;height:80px\" /></a>";
    }

    public function showActions($null, $row) {
        $url = buildUrl(array('action' => 'delete_promote', 'item_id' => $row['item_id']));
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
        else if ('delete_promote' == $_GET['action']) {
            $item_id = $_GET['item_id'];
            $table = $wpdb->prefix . TAOBAOKE_PROMOTE_TABLE;
            $wpdb->query("DELETE FROM {$table} WHERE `item_id` = '$item_id'");
        }
    }

    $fav_controller = new MyFavItems();
    $fav_table = new Table($fav_controller, $fav_controller->getColumns(), $fav_controller->getDataSource());
    $vars['fav_table'] = $fav_table;

    $promote_controller = new MyPromoteItems();
    $promote_table = new Table($promote_controller, $promote_controller->getColumns(), $promote_controller->getDataSource());
    $vars['promote_table'] = $promote_table;

    return $vars;
}
?>
