<?php
class CatController {
    public function getColumns() {
        return array(
               'cid' => array(
                    'header' => 'cid',
                    'function' => 'showCatsDetail',
                    'sortable' => false,
                    ),
            );
    }

    public function getDatasource() {
        $cid = empty($_GET['cid']) ? 0 : $_GET['cid'];
        return new TaobaokeCatsDataSource(array('parent_cid' => $cid));
    }

    public function showCatsDetail($cid, $row) {
        $params = array('cid' => $row['cid'], 'name' => $row['name'], 'is_parent' => $row['is_parent']);

        return '<a href="' . buildUrl($params) . '">' . $row['name'] . '</a>';
    }
}

class ItemController {
    public function getColumns() {
        return array(
            'title' => array(
                'header' => '商品推广信息',
                'sortable' => false,
                'header_style' => 'width:30%',
                'function' => 'showItemDetail'
            ),
            'price' => array(
                'header' => '单价',
                'sortable' => true,
            ),
            'commission' => array(
                'header' => '佣金',
                'sortable' => true,
            ),
            'commission_volume' => array(
                'header' => '佣金总支出'
            ),
            'commission_num' => array(
                'header' => '累计推广量',
                'sortable' => true,
            ),
            'action' => array(
                'header' => '操作',
                'sortable' => false,
                'cell_style' => 'text-align:left',
                'function' => 'showActions'
            )
        );
    }

    public function getDatasource() {
        $pid = var_get('pid');
        $cid = empty($_GET['cid']) ? 0 : $_GET['cid'];

        $search = array('cid' => $cid);

        $search_keyword = empty($_GET['taobaoke_item_search']) ? NULL : $_GET['taobaoke_item_search'];

        if (null != $_GET['taobaoke_item_search'])  {
            $search['keyword'] = $search_keyword;
        }

        return new TaobaokeItemsDataSource($pid, $search, array('commission_num' => 'desc'));
    }

    public function showActions($id, $row) {
        $cid = empty($_GET['cid']) ? 0 : $_GET['cid'];
        $name = empty($_GET['name']) ? 'no-name' : $_GET['name'];

        $promote_url = array('page' => 'taobaoke-actions.php', 'action' => 'promote', 'item_id' => $row['iid'], 'item_title' => $row['title'], 'item_pic' => $row['pic_url'], 'item_url' => urlencode($row['click_url']), 'price'=>$row['price'], 'cid' => $cid, 'name' => $name, 'TB_iframe' => 'true', 'width' => 780, 'height' => 450);
        $cart_url = array('page' => 'taobaoke-actions.php', 'action' => 'cart', 'item_id' => $row['iid'], 'item_title' => $row['title'], 'item_pic' => $row['pic_url'], 'item_url' => urlencode($row['click_url']), 'price'=>$row['price'], 'cid' => $cid, 'name' => $name, 'TB_iframe' => 'true', 'width' => 780, 'height' => 450);
        $shop_promote_url = array('page' => 'taobaoke-actions.php', 'action' => 'shop', 'shop_owner' => $row['nick'], 'TB_iframe' => 'true', 'width' => 780, 'height' => 450);

        return "<a class='thickbox' title='加入推广列表' href='" . buildRawUrl($cart_url) . "' style='color:blue;text-decoration:none'>放入推广列表</a><br />" .
               "<a class='thickbox' title='推广商品' href='" . buildRawUrl($promote_url) . "' style='color:blue;text-decoration:none'>推广此商品</a><br />" .
               "<a class='thickbox' title='推广店铺' href='" . buildRawUrl($shop_promote_url) . "' style='color:blue;text-decoration:none'>推广该店铺</a><br />";
    }

    public function showItemDetail($title, $row) {
        $item_img = $row['pic_url'];
        $item_url = $row['click_url'];
        $shop_url = buildRawUrl(array('page' => 'taobaoke-actions.php', 'action' => 'shop', 'shop_owner' => $row['nick'], 'TB_iframe' => 'true', 'width' => 780, 'height' => 450));

        return <<<ITEM_DETAIL
    <table>
      <tr>
        <td><a class="thickbox" rel="淘宝图片" title="{$title}" href="$item_img"><img src="$item_img" style="width:72px;height:80px"/></a></td>
        <td align="left">
            <a title="{$title}" class="thickbox" href="{$item_url}&TB_iframe=true&width=640&height=524" >$title</a><br />
            <span >掌柜：<a href="{$shop_url}" title="推广该店铺" alt="推广该店铺" class="thickbox" >{$row['nick']}</a></span>
        </td>
      </tr>
    </table>
ITEM_DETAIL;
    }
}

function display_page() {
    $vars = array();

    $has_cats = empty($_GET['is_parent']) ? (empty($_GET['cid']) ? true : false) : true;
    $vars['has_cats'] = $has_cats;

    if (!empty($_GET['name'])) {
        $vars['taobaoke_cur_cat'] = $_GET['name'];
    }

    $vars['query_string'] = explode_query_string();
    unset($vars['query_string']['taobaoke_item_search']);
    unset($vars['query_string']['taobaoke_item_search_button']);

    if ($has_cats) {
        $controller = new CatController();

        $taobaoke_cats_table = new Table($controller, $controller->getColumns(), $controller->getDatasource());
        $taobaoke_cats_table->setGridTableColumn(3);
        $taobaoke_cats_table->setNoRecordLabel('当前分类没有二级分类，请查看该类目下的商品');

        $vars['taobaoke_cats_table'] = $taobaoke_cats_table;
    }

    $has_items = empty($_GET['cid']) && empty($_GET['taobaoke_item_search'])? false : true;
    $vars['has_items'] = $has_items;

    if (!empty($_GET['taobaoke_item_search'])) {
        $site_url = get_bloginfo('wpurl');
        taobaoke_anaylysis(array('type' => 'search', 'site_url' => $site_url, 'item_id' => 'taobaoke-product-search', 'item_name' => $_GET['taobaoke_item_search']));
    }

    if ($has_items) {
        $item_controller = new ItemController();
        $item_table = new Table($item_controller, $item_controller->getColumns(), $item_controller->getDatasource());
        $item_table->setNoRecordLabel('该类目下没有商品信息');
        $item_table->setDefaultOrder('commission_num',  'DESC');

        $vars['taobaoke_item_table'] = $item_table;
    }

    return $vars;
}
?>
