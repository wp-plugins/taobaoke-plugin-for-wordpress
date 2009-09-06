<?php
/**
 * 以数据库为数据源获取数据。
 *
 * @author wyatt.fang
 */
class DatabaseDataSource extends DataSource {

    protected $_query;
    protected $_sort_mode;
    protected $_limits;

    public function __construct($query, $initial_sort_mode, $limits) {
        parent::__construct(new Condition(NULL, $initial_sort_mode, array(0, $limits[1])));

        $this->_query = $query;
        $this->_sort_mode = $initial_sort_mode;
        $this->_limits = $limits;
    }

    /**
     * @see DataSource::getTotal
     */
    public function getTotal() {
        global $wpdb;

        return count($wpdb->get_results($this->_query));
    }

   /**
    * @see DataSource::getData
    */
    public function getData() {
        global $wpdb;

        $sql_with_limit = $this->constructSqlWithLimit();

        $rows = $wpdb->get_results($sql_with_limit, ARRAY_A);

        return $rows;
    }

    /**
    * @see DataSource::getDataCount
    */
    public function getDataCount() {
        global $wpdb;

        $sql_with_limit = $this->constructSqlWithLimit();

        return count($wpdb->get_results($sql_with_limit));
    }

    private function constructSqlWithLimit() {
        $data_condition = $this->getCondition();
        $limit = $data_condition->getLimits();
        $sql_with_limit = ' LIMIT ' . $limit[0] . ', ' . $limit[1];
        $order_by = '';
        if($data_condition->getSortMode()){
            $orders = $data_condition->getSortMode();
            foreach ($orders as $filed => $order) {
                $order_by_arr[] .= "$filed $order";
            }

            $order_by = ' ORDER BY ' . implode(',', $order_by_arr);
        }

        $sql_with_limit_and_order_by = $order_by . $sql_with_limit;

        return $this->_query . ' ' . $sql_with_limit_and_order_by;
    }
}
?>
