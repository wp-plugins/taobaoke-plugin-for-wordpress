<?php
class TaobaokeItemsGetRequest extends Request {
    private $__accepted_sort_fields = array(
            'price', 'credit', 'commission', 'commissionRate',
            'commissionNum', 'commissionVolume', 'delistTime',
        );

    public function setFields($fields = null) {
        if (null == $fields) {
            $fields = 'iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume';
        }

        $this->prop['fields'] = $fields;
    }

    public function setPid($pid) {
        $this->prop['pid'] = $pid;
    }

    public function setKeyword($keyword) {
        $this->prop['keyword'] = $keyword;
    }

    public function setCid($cid) {
        $this->prop['cid'] = $cid;
    }

    public function setStartNum($start) {
        $this->prop['page_no'] = $start;
    }

    public function setPageNum($page_count) {
        $this->prop['page_size'] = $page_count;
    }

    public function setStartPrice($start_price) {
        $this->prop['start_price'] = $start_price;
    }

    public function setEndPrice($end_price) {
        $this->prop['end_price'] = $end_price;
    }

    public function setStartCredit($start_credit) {
        $this->prop['start_credit'] = $start_credit;
    }

    public function setEndCredit($end_credit) {
        $this->prop['end_credit'] = $end_credit;
    }

    public function setSortOrder($field, $order) {
        if (strpos($field, '_')) {
            $field_array = explode('_', $field);
            $num = count($field_array);

            for ($i = 0; $i < $num; $i++) {
                if ($i > 0) {
                    $field .= ucfirst($field_array[$i]);
                }
                else {
                    $field = $field_array[$i];
                }
            }
        }

        if (in_array($field, $this->__accepted_sort_fields)) {
            $this->prop['sort'] = $field . '_' . strtolower($order);
        }
        else {
            $this->prop['sort'] = 'default';
        }

    }

    public function setStartCommission($start_commission) {
        $this->prop['start_commission'] = $start_commission;
    }

    public function setEndCommission($end_commission) {
        $this->prop['end_commission'] = $end_commission;
    }

    public function setStartCommissionRate($start_commission_rate) {
        $this->prop['start_commissionRate'] = $start_commission_rate;
    }

    public function setEndCommissionRate($end_commission_rate) {
        $this->prop['end_commissionRate'] = $end_commission_rate;
    }

    public function setStartCommissionNum($start_commission_num) {
        $this->prop['start_commissionNum'] = $start_commission_num;
    }

    public function setEndCommissionNum($end_commission_num) {
        $this->prop['end_commissionNum'] = $end_commission_num;
    }
}
?>
