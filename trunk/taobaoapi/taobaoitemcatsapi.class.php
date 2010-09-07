<?php
class TaobaoItemCatsApi extends TaobaoApi {
    public function getItemCats(ItemCatsGetV2Request $request) {
        //返回的格式为:array('last_modified' => '', 'item_cats' => array(0=>obj, 1=>obj))
        $result = $this->sendRequest($request, 'TaobaoItemCatsApi:getItemCats', 'taobao.itemcats.get');

        return $result['itemcats_get_response'];
    }
}
?>
