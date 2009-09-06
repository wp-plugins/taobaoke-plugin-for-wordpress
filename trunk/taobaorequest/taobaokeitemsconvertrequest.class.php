<?php
class TaobaokeItemsConvertRequest extends Request {

    public function setFields($fields = null) {
        if (null == $fields) {
            $fields = 'iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commision_volume';
        }

        $this->prop['fields'] = $fields;
    }

    public function setIids($iids) {
        $this->prop['iids'] = $iids;
    }

    public function setNick($nick) {
        $this->prop['nick'] = $nick;
    }

    public function setOutCode($outer_code) {
        $this->prop['outer_code'] = $outer_code;
    }
}
?>
