<?php
class TaobaoApi {
    protected $_params = array( //common fields
        'format'    => 'json',
        'v'         => '2.0',
        'sign_method' => 'md5',
        'fields'    => '',
        );

    public function __construct($params = array()) {
        $this->_params['app_key'] = var_get('appkey');
        $this->_params['timestamp'] = date('Y-m-d H:i:s');

        //merge $params with $this->_params
        foreach ($params as $key => $value) {
            $this->_params[$key] = $value;
        }
    }

    protected function parseJsonResult($json_result, $taobao_api = 'taobao.api') {
        $json_parsed = Json::jsonDecode($json_result);

        if (array_key_exists('error_rsp', $json_parsed)) { //查询失败
            $error_code = $json_parsed['error_rsp']['code'];
            $error_msg = $json_parsed['error_rsp']['msg'];

            throw new ApiException($error_msg, $error_code, $taobao_api);
        }
        else if (is_array($json_parsed['rsp']) && 1 > count($json_parsed['rsp'])){
            throw new ApiNoResultException('Result is empty!', -1, $taobao_api);//-1的意思是当前查询没有数据返回
        }
        else {
            return $json_parsed;
        }
    }

    protected function sendRequest(Request $request, $method, $api) {
        $this->isNotNull($request, $method, 'request');

        foreach ($request->getProp() as $key => $value) {
            $this->_params[$key] = $value;
        }

        $this->_params['method'] = $api;

        $item_result_json = Util::getResult($this->_params);

        if (DEBUG) {
            log_message($item_result_json);
        }

        $entity_result = null;

        try {
            $entity_result = $this->parseJsonResult($item_result_json, $api);

            if (DEBUG) {
                log_message($entity_result);
            }
        }
        catch (ApiException $ex) {
            handle_exception($ex);
        }
        catch (ApiNoResultException $ex) {
            handle_exception($ex);
        }

        return $entity_result;
    }

    protected function isNotNull($param, $method, $param_name) {
        if (null == $param) {
            throw new ApiException(parse_string(ErrorMessage::API_PARAM_NULL, $method, $param_name),
                                     ErrorMessage::API_PARAM_NULL_CODE, $method);
        }
    }
}
