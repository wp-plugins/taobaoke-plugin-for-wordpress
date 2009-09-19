<?php
/**
 * Description of condition
 *
 * @author wyatt.fang
 */
class Condition {
    private $where;
    private $sort_mode;
    private $limits;

    /**
     *
     * @param Array $conditon, 过滤条件数组；格式如下：
     *                 array ('where' => array("`name` = 'wyatt'" => 'AND', "`des` like %%" => 'AND'))
     * @param Array $sort_mode
     *                example: $sort_mode('name' => 'ASC', 'age' => 'DESC')先按照名字升序排，再按照年龄降序排
     * @param Array $limits = array(0, 10);  从偏移量0的位置开始搜索10调记录。
     */
    public function __construct($where, $sort_mode, $limits) {
        $this->where = $where;
        $this->sort_mode = $sort_mode;
        $this->limits = $limits;
    }

    public function getWhere() {
        return $this->where;
    }

    public function setWhere($where) {
        $this->where = $where;
    }

    /**
     * 设置排序的方式。
     * @param Array $sort_mode
     *                example: $sort_mode('name' => 'ASC', 'age' => 'DESC')先按照名字升序排，再按照年龄降序排
     */
    public function setSortMode($sort_mode) {
        $this->sort_mode = $sort_mode;

        return $this->sort_mode;
    }

    /**
     * 获取排序方式。
     * @return Array $sort_mode
     *                example: $sort_mode('name' => 'ASC', 'age' => 'DESC')先按照名字升序排，再按照年龄降序排
     */
    public function getSortMode() {
        return $this->sort_mode;
    }

    /**
     * 设置搜索个数和偏移量。
     * @param Array $limits = array(0, 10);  从偏移量0的位置开始搜索10调记录。
     */
    public function setLimits($limits) {
        $this->limits = $limits;
    }

    /**
     * 获取搜索的个数和偏移量设置。
     * @return Array $limits = array(0, 10);  从偏移量0的位置开始搜索10调记录。
     */
    public function getLimits() {
        return $this->limits;
    }
}
?>
