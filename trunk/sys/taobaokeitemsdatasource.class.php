<?php
class TaobaokeItemsDataSource extends DataSource {
    private $pid;
    private $search;
    private $request;
    private $limit;

    protected $_total = null;
    protected $_total_data = null;
    protected $_data_couont = null;
    protected $page_per_count;

    /**
     * @param $search is an array, which accepted two keys in it, the two keys are:
     *        'cid' = $cid, //检索商品的分类
     *        'keyword' = $keyword //按关键字搜索商品
     * @param $sort is an array, which's format is array('field', 'asc' or 'desc'), the field must be in the range of :
     *        'price', 'credit', 'commission', 'commission_rate',
     *        'commission_num', 'commission_volume', 'delist_time',
     */
    public function __construct($pid, $search, $sort = null, TaobaokeItemsGetRequest $request = null, $limit = array(0, 20)) {
        parent::__construct(new Condition(null, array($sort[0] => $sort[1]), $limit));

        $this->pid = $pid;
        $this->page_per_count = $limit[1];

        $this->_taobao_api = new TaobaokeApi();
        if (null != $request) {
            $this->request = $request;
        }
        else {
            $this->request = new TaobaokeItemsGetRequest();
        }

        if (null != $sort) {
            $this->request->setSortOrder($sort[0], $sort[1]);
        }

        $this->request->setStartNum($limit[0] + 1);
        $this->request->setPageNum($limit[1]);
        $this->request->setPid($pid);

        if (array_key_exists('cid', $search)) {
            $this->request->setCid($search['cid']);
        }
        if (array_key_exists('keyword', $search)) {
            $this->request->setKeyword($search['keyword']);
        }

        $this->request->setFields();
    }

    public function getTotal() {
        $this->sendRequest();

        return $this->_total;//淘宝API bug，不能获取所有的页数，只能显示前100页...
    }

    public function getData() {
        $this->sendRequest();

        return $this->_total_data;
    }

    public function getDataCount() {
        //$this->sendRequest();//table class will call getData firstly, we will initial the data_count in that request

        return $this->_data_count;
    }

    protected function initialRequest() {
        $sort = $this->condition->getSortMode();
        $sort_keys = array_keys($sort);
        $this->request->setSortOrder($sort_keys[0], $sort{$sort_keys[0]});

        $limit = $this->condition->getLimits();
        $this->request->setStartNum(($limit[0] / $limit[1]) + 1);
        $this->request->setPageNum($limit[1]);
    }

    protected function sendRequest() {
        try {
            $this->initialRequest();

            $items = $this->_taobao_api->getItems($this->request);

            $this->_total_data = $items['taobaoke_items']['taobaoke_item'];
            $this->_data_count = count($this->_total_data);

            if ($this->_data_count > 0) {
                $this->_total = 100 * $this->page_per_count;
            }
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
?>
