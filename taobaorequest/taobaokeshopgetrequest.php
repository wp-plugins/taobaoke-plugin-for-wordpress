<?php
class TaobaokeShopGetRequest extends Request {
    public function setFields($fields = null) {
        if (null == $fields) {
            $fields = 'sid,cid,nick,title,desc,bulletin,pic_path,created,modified';
        }

        $this->prop['fields'] = $fields;
    }

    public function setNick($nick) {
        $this->prop['nick'] = $nick;
    }
}
