<?php
class TaobaokeActivityDataSource extends DataSource {
    protected $_api_url;

    protected $_total = null;
    protected $_total_data = null;
    protected $_data_count = null;

    //cids is an array, which should have key 'parent_cid' or 'cids'
    public function __construct() {
        parent::__construct(new Condition(null, null, null));

        $this->_api_url = API_URL . 'service/promotions';

        $result = get_activities();

        if (isset($result['totalCount'])) {
            $this->_total = $result['totalCount'];
            $this->_total_data = $result['promotions'];
            $this->_data_count = $this->_total;
        }
        else {
            $this->_total = 0;
            $this->data_count = 0;
            $this->_total_data = array();
        }
    }

    public function getTotal() {
        return $this->_total;
    }

    public function getData() {
        return $this->_total_data;
    }

    public function getDataCount() {
        return $this->_data_count;
    }
}
