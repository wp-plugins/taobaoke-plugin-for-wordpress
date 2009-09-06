<?php
class TaobaoUserApi extends TaobaoApi {

    public function __construct($params = null) {
        if (null == $params) {
            $params = array();
            $params['fields'] = 'user_id,nick,sex,buyer_credit,seller_credit,location.city,location.state,location.country,created,last_visit,location.zip';
        }

        parent::__construct($params);
    }

    public function getUser($nickname) {
        //Parameter checker..TODO

        $this->_params['method'] = 'taobao.user.get';
        $this->_params['nick'] = $nickname;

        $user_josn = Util::getResult($this->_params);

        //验证返回结果
        $user_returned = Json::jsonDecode($user_josn);
        if (array_key_exists('error_rsp')) { //查询失败，如果用户不存在，则会失败
            $error_code = $user_returned['error_rsp']['code'];
            $error_msg = $user_returned['error_rsp']['msg'];

            throw new ApiException($error_msg, $error_code, 'taobao.user.get');
        }
        else {
            return $user_returned['rsp']['users'][0];//见下面的注释，关于返回结果的格式
        }
    }

    public function getUsers($nickname_array) {
        if (!is_array($nickname_array)) {
            throw new ApiException('', 1, 'taobao.users.get');//TODO, Parameter checker...
        }

        $this->_params['method'] = 'taobao.users.get';
        $this->_params['nicks'] = implode(',', $nickname_array);

        $users_josn = Util::getResult($this->_params);

        //验证返回结果
        if (array_key_exists('error_rsp')) { //查询失败，如果用户不存在，则会失败
            $error_code = $user_returned['error_rsp']['code'];
            $error_msg = $user_returned['error_rsp']['msg'];

            throw new ApiException($error_msg, $error_code, 'taobao.users.get');
        }
        else {
            return $user_returned['rsp']['users'];//见下面的注释，关于返回结果的格式
        }
    }

    /**返回的结果的格式为：
            Array
            (
                [rsp] => Array
                    (
                        [users] => Array
                            (
                                [0] => Array
                                    (
                                        [buyer_credit] => Array
                                            (
                                                [good_num] => 2
                                                [level] => 0
                                                [score] => 2
                                                [total_num] => 2
                                            )

                                        [created] => 2008-03-07 23:23:22
                                        [last_visit] => 2009-03-12 19:41:59
                                        [location] => Array
                                            (
                                            )

                                        [nick] => alipublic01
                                        [seller_credit] => Array
                                            (
                                                [good_num] => 0
                                                [level] => 0
                                                [score] => 0
                                                [total_num] => 0
                                            )

                                        [user_id] => 175754351
                                    )

                            )

                    )

            )
            **/
}
