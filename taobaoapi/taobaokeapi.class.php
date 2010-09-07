<?php
class TaobaokeApi extends TaobaoApi {
    public function __construct() {
        parent::__construct();
    }

    public function getItems(TaobaokeItemsGetRequest $request) {
        //The structure of the $entity_result is: $entity_result = array('taobaokeItems' => array(0=>obj, 1=>obj), 'totalResults' => 100)
        $result = $this->sendRequest($request, 'TaobaokeApi:getItems', 'taobao.taobaoke.items.get');

        log_message($result);

        return $result['taobaoke_items_get_response'];
    }

    public function convertItems(TaobaokeItemsConvertRequest $request) {
        //The structure of the $entity_result is: $entity_result = array('taobaokeItems' => array(0=>obj, 1=>obj))
        $result =  $this->sendRequest($request, 'TaobaokeApi:convertItems', 'taobao.taobaoke.items.convert');

        return $result['taobaoke_items_convert_response'];
    }

    public function getShop(TaobaokeShopGetRequest $request) {
        $result = $this->sendRequest($request, 'TaobaokeApi:getShop', 'taobao.shop.get');

        return $result['shop_get_response'];
    }

    public function convertShop(TaobaokeShopConvertRequest $request) {
        $result = $this->sendRequest($request, 'TaobaokeApi:convertShop', 'taobao.taobaoke.shops.convert');

        return $result['taobaoke_shops_convert_response'];
    }

    public function getSearchUrl(TaobaokeGetSearchUrlRequest $request) {
        $result = $this->sendRequest($request, 'TaobaokeApi:getSearchUrl', 'taobao.taobaoke.listurl.get');

        return $result['taobaoke_listurl_get_response'];
    }
}
?>
