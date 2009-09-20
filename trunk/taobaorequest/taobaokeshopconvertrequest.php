<?php
class TaobaokeShopConvertRequest extends Request {
    public function setFields($fields = null) {
        if (null == $fields) {
            $fields = 'user_id,shop_title,click_url,shop_commission.rate';
        }

        $this->prop['fields'] = $fields;
    }

    public function setSids($sids) {
        $this->prop['sids'] = $sids;
    }

    public function setNick($nick) {
        $this->prop['nick'] = $nick;
    }

    public function set0uterCode($outer_code) {
        $this->prop['outer_code'] = $outer_code;
    }
}
