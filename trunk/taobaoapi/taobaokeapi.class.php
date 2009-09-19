<?php
class TaobaokeApi extends TaobaoApi {
    public function __construct() {
        parent::__construct();
    }

    public function getItems(TaobaokeItemsGetRequest $request) {
        //The structure of the $entity_result is: $entity_result = array('taobaokeItems' => array(0=>obj, 1=>obj), 'totalResults' => 100)
        return $this->sendRequest($request, 'TaobaokeApi:getItems', 'taobao.taobaoke.items.get');
    }

    public function convertItems(TaobaokeItemsConvertRequest $request) {
        //The structure of the $entity_result is: $entity_result = array('taobaokeItems' => array(0=>obj, 1=>obj))
        return $this->sendRequest($request, 'TaobaokeApi:convertItems', 'taobao.taobaoke.items.convert');
    }

    public function getShop(TaobaokeShopGetRequest $request) {
        return $this->sendRequest($request, 'TaobaokeApi:getShop', 'taobao.shop.get');
    }

    public function convertShop(TaobaokeShopConvertRequest $request) {
        return $this->sendRequest($request, 'TaobaokeApi:convertShop', 'taobao.taobaoke.shops.convert');
    }

    public function getSearchUrl(TaobaokeGetSearchUrlRequest $request) {
        return $this->sendRequest($request, 'TaobaokeApi:getSearchUrl', 'taobao.taobaoke.listurl.get');
    }
}
?>
