<?php
class TaobaokeCatsDataSource extends DataSource {
    protected $_taobao_cat_api;
    protected $_cids;

    protected $_total = null;
    protected $_total_data = null;
    protected $_data_count = null;

    //cids is an array, which should have key 'parent_cid' or 'cids'
    public function __construct($cids) {
        parent::__construct(new Condition(null, null, null));

        $this->_cids = $cids;

        $this->_taobao_cat_api = new TaobaoItemCatsApi();
    }

    public function getTotal() {
        if (is_not_empty($this->_total)) {
            return $this->_total;
        }
        else {
            $this->_initialSearch();
        }

        return $this->_total;
    }

    public function getData() {
        if (is_not_empty($this->_total_data)) {
            return $this->_total_data;
        }
        else {
            $this->_initialSearch();
        }

        return $this->_total_data;
    }

    public function getDataCount() {
        if (is_not_empty($this->_data_count)) {
            return $this->_data_count;
        }
        else {
            $this->_initialSearch();
        }

        return $this->_data_count;
    }

    protected function _initialSearch() {
        try {
            $request = new ItemCatsGetV2Request();
            $request->setFields();
            if (array_key_exists('parent_cid', $this->_cids)) {
                $request->setParentCid($this->_cids['parent_cid']);
            }
            else if (array_key_exists('cids', $this->_cids)) {
                $request->setCids($this->_cids['cids']); //逗号分隔
            }

            $result = $this->_taobao_cat_api->getItemCats($request);

            $cat_items = $result['item_cats']['item_cat'];

            $this->_total = count($cat_items);
            $this->_total_data = $cat_items;
            $this->_data_count = $this->_total;
        }
        catch (ApiException $ex) {
            //Log
            $this->_total = 0;
            $this->_total_data = null;
            $this->_data_count = 0;

            //print_var($ex);
        }
        catch (ApiNoResultException $ex) {
            //Log
            $this->_total = 0;
            $this->_total_data = null;
            $this->_data_count = 0;

            //print_var($ex);
        }
        catch (Exception $ex) {
            //Log
            $this->_total = 0;
            $this->_total_data = null;
            $this->_data_count = 0;

            //print_var($ex);
        }
    }
}
