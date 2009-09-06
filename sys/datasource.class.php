<?php
/**
 * Description of datasourceinterface
 *
 * @author wyatt.fang
 */
abstract class DataSource {
    /**
     * @var Condition
     */
    protected $condition;

    /**
     * @param Condition $condition
     */
    public function __construct(Condition $condition) {
        $this->condition = $condition;
    }

    /**
     * 获取满足搜索条件的数量。
     */
    public abstract function getTotal();

    /**
     * 获取满足搜索条件同时满足limit条件的数据。
     */
    public abstract function getData();

    /**
     * 获取满足搜索条件同时满足limit条件的数据的数量。
     */
    public abstract function getDataCount();

    /**
     * 获取搜索条件。
     * @return Condition
     */
    public function getCondition() {
        return $this->condition;
    }

    /**
     * 设置搜索条件。
     * @param Condition $condition
     */
    public function setCondition(Condition $condition) {
        $this->condition = $condition;
    }
}
?>