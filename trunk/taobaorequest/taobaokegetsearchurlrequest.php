<?php
class TaobaokeGetSearchUrlRequest extends Request {
    public function setKeyword($k) {
        $this->prop['q'] = $k;
    }

    public function setNick($nick) {
        $this->prop['nick'] = $nick;
    }

    public function setOuterCode($outer_code) {
        $this->prop['outer_code'] = $outer_code;
    }
}
