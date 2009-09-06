<?php
class ItemCatsGetV2Request extends Request {
    public function setFields($fields = null)  {
        if (null == $fields) {
            $fields = 'cid,parent_cid,name,is_parent,status,sort_order';
        }

        $this->prop['fields'] = $fields;
    }

    public function setParentCid($parent_cid) {
        $this->prop['parent_cid'] = $parent_cid;
    }

    public function setCids($cids) {
        $this->prop['cids'] = $cids;
    }
}
?>
