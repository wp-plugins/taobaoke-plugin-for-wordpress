<?php
class TaobaokeItemDetailGetRequest extends Request {
    public function setFields($fields = null) {
        if (null == $fields) {
            $fields = 'num_iid,title,nick,pic_url,price,delist_time';
        }

        $this->prop['fields'] = $fields;
    }
    
    public function setIids($iids) {
        $this->prop['num_iids'] = $iids;
    }

    public function setNick($nick) {
        $this->prop['nick'] = $nick;
    }

    public function setOuterCode($code = 'blog') {
        $this->prop['outer_code'] = $code;
    }
}
?>
