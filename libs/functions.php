<?php
define('QUERY_REGEXP', '/(%d|%s|%%|%f|%b|%n)/');
define('PLUGIN_PREFIX', 'taobaoke-wordpress-plugin-');

function taobaoke_cache_get($key) {
}

function taobaoke_cache_set($key, $value, $expires) {
}

function pid_get() {
    global $current_user;
    //get_currentuserinfo();

    $var_key = PLUGIN_PREFIX . $current_user->ID . '-pid';

    $value = get_option($var_key, null);
    if (null == $value) {
        $var_key = PLUGIN_PREFIX . '1' . '-pid';
        $value = get_option($var_key, TOP_PID);
    }

    return stripslashes($value);
}

function pid_set($value) {
    global $current_user;
    //get_currentuserinfo();

    $var_key = PLUGIN_PREFIX . $current_user->ID . '-pid';

    if (null != get_option($var_key, null)) {
        update_option($var_key, $value);
    }
    else {
        add_option($var_key, $value);
    }
}

function appkey_get() {
    $default = '12004702';

    return $default;
}

function appsecret_get() {
    $default = '08a096729647d3db476bcddfe06dbc97';

    return $default;
}

function nickname_get() {
    global $current_user;
    //get_currentuserinfo();

    $var_key = PLUGIN_PREFIX . $current_user->ID . '-nickname';

    $value = get_option($var_key, null);

    if (null == $value) {
        $var_key = PLUGIN_PREFIX . '1' . '-nickname';
        $value = get_option($var_key, TOP_NICK);
    }

    return $value;
}

function nickname_set($value) {
    global $current_user;
    //get_currentuserinfo();

    $var_key = PLUGIN_PREFIX . $current_user->ID . '-nickname';

    if (null != get_option($var_key, null)) {
        update_option($var_key, $value);
    }
    else {
        add_option($var_key, $value);
    }
}

function var_get($var_key, $var_default = null) {
    if (function_exists($var_key . '_get')) {
        $value = call_user_func($var_key . '_get');

        if (null != $value) {
            return $value;
        }
    }

    $var_key = PLUGIN_PREFIX . $var_key;
    $value = get_option($var_key, $var_default);

    return $value;
}

function var_set($var_key, $var_value) {
    if (function_exists($var_key . '_set')) {
        return call_user_func($var_key . '_set', $var_value);
    }

    $value = var_get($var_key, null);

    if (null !== $value) {
        update_option(PLUGIN_PREFIX . $var_key, $var_value);
    }
    else if ('' === $value) {
        update_option(PLUGIN_PREFIX . $var_key, $var_value);
    }
    else {
        add_option(PLUGIN_PREFIX . $var_key, $var_value);
    }
}

function var_delete($var_key) {
    delete_option(PLUGIN_PREFIX . $var_key);
}

function taobaoke_img_path() {
    $img_url = WP_PLUGIN_URL . '/' . TAOBAOKE_PLUGIN_FOLDER . '/images/';
    return $img_url;
}

function taobaoke_css_path() {
    $css_url = WP_PLUGIN_URL . '/' . TAOBAOKE_PLUGIN_FOLDER . '/css/';
    return $css_url;
}

function taobaoke_tpl_path() {
    return TAO_PATH . 'tpl/';
}

function taobaoke_js_path() {
    $js_url = WP_PLUGIN_URL . '/' . TAOBAOKE_PLUGIN_FOLDER . '/js/';
    return $js_url;
}

function _e_($message) {
    _e($message);
}

function log_message($message) {
    if (DEBUG) {
        $logging_message = '';

        if (is_array($message)) {
            $logging_message = print_r($message, true);
        }
        else {
            $logging_message = $message;
        }

        $log_file = dirname(dirname(__FILE__)) . '/' . 'logging.txt';

        try {
            file_put_contents($log_file, '**************** - ' . date('Y-m-d H:i:s') . ' -*************************' . "\r\n", FILE_APPEND);
            file_put_contents($log_file, $logging_message, FILE_APPEND);
            file_put_contents($log_file, "\r\n", FILE_APPEND);
        }
        catch (Exception $e) {
            //TODO
        }   
    }
}

function print_var($var) {
    if (DEBUG) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

function is_empty($value) {
    return !is_not_empty($value);
}

define('TAOBAOKE_PLUGIN_OWNER_URL', 'http://dafang-blog.appspot.com/save?');
define('TAOBAOKE_PLUGIN_OWNER_URL_T', TAO_URL . 'analysis.php?');
function taobaoke_anaylysis($info) {
    $query = '';
    foreach ($info as $key => $value) {
        $query .= $key . '=' . urlencode($value) . '&';
    }

    $snoopy = new Snoopy();
    $query = substr($query, 0, -1);

    try {

        $url = TAOBAOKE_PLUGIN_OWNER_URL . $query;
        $successed = $snoopy->fetch($url);
    }
    catch (Exception $ex) {
        //Pass
    }
}

function is_not_empty($value) {
    if (is_array($value)) {
        if (count($value) > 0) {
            return true;
        }
        else {
            return false;
        }
    }
    else if (!is_null($value)) {
        $value = trim($value);
        if ($value === '') {
            return false;
        }
        else {
            return true;
        }
    }
    else {
        return false;
    }
}

function __autoload($class_name) {
    $file_partial_name = strtolower($class_name);

    $include_folder = array('libs/', 'taobaoapi/', 'taobaorequest/', 'exception/', 'const/', 'sys/');

    foreach ($include_folder as $folder) {
        if (file_exists(TAO_PATH . $folder . $file_partial_name . '.class.php')) {
            require_once(TAO_PATH . $folder . $file_partial_name . '.class.php');

            return;
        }
        else if (file_exists(TAO_PATH . $folder . $file_partial_name . '.php')) {
            require_once(TAO_PATH . $folder . $file_partial_name . '.php');

            return;
        }
    }
}

function buildUrl($new_query_string_pram) {
    $query_string = addQueryString($new_query_string_pram);
    $url = $_SERVER["SCRIPT_URI"];

    return $url . '?' . $query_string;
}

function buildRawUrl($query_string) {
    $url = $_SERVER["SCRIPT_URI"];

    $url .= '?';

    foreach ($query_string as $key => $value) {
        $url .= $key . '=' . $value . '&';
    }

    return substr($url, 0, -1);
}

function explode_query_string() {
    $parameters = NULL;
    $query_string = Common::getQueryString();

    if ('' != $query_string) {
        $parameters = explode('&', $query_string);
    }

    $new_parameters = array();
    for ($i = 0; $i < count($parameters); $i++) {
        $parameter = explode('=', $parameters[$i]);
        $key = $parameter[0];
        $value = isset($parameter[1]) ? $parameter[1] : '';
        $new_parameters[$key] = $value;
    }

    return $new_parameters;
}

function addQueryString($parameter_array) {
        $parameters = NULL;
        $query_string = Common::getQueryString();

        if ('' != $query_string) {
            $parameters = explode('&', $query_string);
        }

        $new_parameters = array();
        for ($i = 0; $i < count($parameters); $i++) {
            $parameter = explode('=', $parameters[$i]);
            $key = $parameter[0];
            $value = isset($parameter[1]) ? $parameter[1] : '';
            $new_parameters[$key] = $value;
        }

        foreach ($parameter_array as $p_key => $p_value) {
            $new_parameters[$p_key] = $p_value;
        }

        $new_query_string = http_build_query($new_parameters);

        return $new_query_string;
    }

/**
 * Checks whether a string is valid UTF-8.
 * @param $text
 *   The text to check.
 * @return
 *   TRUE if the text is valid UTF-8, FALSE if not.
 */
function validate_utf8($text) {
    if (strlen($text) == 0) {
      return TRUE;
    }

    return (preg_match('/^./us', $text) == 1);
}

/**
 * Encode special characters in a plain-text string for display as HTML.
 *
 * Uses validate_utf8 to prevent cross site scripting attacks on
 * Internet Explorer 6.
 */
function check_plain($text) {
    return validate_utf8($text) ? htmlspecialchars($text, ENT_QUOTES) : '';
}

/**
 * Gets microsecond from current system.
 * @return float current system time with microsecond
 */
function get_microtime() {
    list($usec, $sec) = explode(' ', microtime());

    return (float)$usec + (float)$sec;
}

 /**
 * Usage sample: parse_string('hi %s', 'Wyatt'); the output is: hi Wyatt.
 * @param $message the message contains %s, %d, %f, %%, %n
 * @param $args
 */
function parse_string($message) {
    $args = func_get_args();
     array_shift($args);

     if (isset($args[0]) && is_array($args[0])) { // 'All arguments in one array' syntax
        $args = $args[0];
     }

    _message_callback($args, TRUE);

    $message = preg_replace_callback(QUERY_REGEXP, '_message_callback', $message);

    return $message;
}

/**
 * Helper function used by parse_string.
 */
function _message_callback($match, $init = FALSE) {
    static $args = NULL;

    if ($init) {
        $args = $match;
        return;
    }

    switch ($match[1]) {
        case '%d': // We must use type casting to int to convert FALSE/NULL/(TRUE?)
          return (int) array_shift($args);

        case '%s':
          return (string) array_shift($args);

        case '%n':
          // Numeric values have arbitrary precision, so can't be treated as float.
          // is_numeric() allows hex values (0xFF), but they are not valid.
          $value = trim(array_shift($args));
          return is_numeric($value) && !preg_match('/x/i', $value) ? $value : '0';

        case '%f':
          return (float) array_shift($args);

        case '%%':
            return '%';

        case '%b': // binary data
            return db_encode_blob(array_shift($args));
    }
}

function handle_exception($ex) {

}

function taobaoke_show_width() {
    $width = var_get('widget_width', TAOBAOKE_SIDEBAR_WIDTH);

    return $width;
}

function taobaoke_show_height() {
    $height = var_get('widget_height', 90);

    return $height;
}

function taobaoke_show_color($item) {
    $color = var_get('widget_color_' . $item);
    if (empty($color)) {
        switch ($item) {
            case 'title':
                $color = TAOBAOKE_SIDEBAR_TITLE_COLOR;
                break;
            case 'bg':
                $color = TAOBAOKE_SIDEBAR_BG_COLOR;
                break;
            case 'price':
                $color = TAOBAOKE_SIDEBAR_PRICE_COLOR;
                break;
            case 'border':
                $color = TAOBAOKE_SIDEBAR_BORDER_COLOR;
                break;
            default:
                $color = '000000';
                break;
        }
    }

    return $color;
}

function sync_hot_keywords($no_cache = false) {
    $hot_keywords = get_hot_keywords();
    if (isset($hot_keywords['totalCount']) && $hot_keywords['totalCount'] > 0) {
        $keywords = $hot_keywords['hotkeywords'];

        foreach ($keywords as $keyword) {
            $keyword = trim($keyword);
            $keyword_url = convert_keyword($keyword, $no_cache);
            if ('#' != $keyword_url) {
                add_hot_keyword_to_db($keyword, $keyword_url);
            }
        }
    } 
}

function get_hot_keyword_from_db($table_name) {
    global $wpdb;

    $table = $wpdb->prefix . $table_name;

    $result = $wpdb->get_results("SELECT `keyword`, `click_url` FROM $table;");

    $keywords = array();
    foreach ($result as $cur) {
        $keywords[$cur->keyword] = $cur->click_url;
    }

    return $keywords;
}

function add_keyword_to_db($keyword, $keyword_url, $table) {
    global $wpdb;
    global $current_user;

    $user_id = isset($current_user) ? $current_user->ID : 1;

    $table = $wpdb->prefix . $table;

    $keyword_count = $wpdb->get_var("SELECT COUNT(*) AS count FROM $table WHERE `user_id` = $user_id AND `keyword` = '$keyword';");
    if ($keyword_count > 0) {
        $wpdb->query("UPDATE $table SET `click_url` = '$keyword_url' WHERE `user_id` = $user_id AND `keyword` = '$keyword';");
    }
    else {
        $wpdb->query("INSERT INTO $table VALUES (NULL, $user_id, '$keyword', '$keyword_url', NOW(), NOW());");
    }
}

function add_hot_keyword_to_db($keyword, $keyword_url) {
    add_keyword_to_db($keyword, $keyword_url, TAOBAOKE_HOT_KEYWORDS);
}

function add_auto_keyword_to_db($keyword, $keyword_url) {
   add_keyword_to_db($keyword, $keyword_url, TAOBAOKE_AUTO_KEYWORDS);
}

function convert_keyword($keyword, $no_cache = false) {
    if (!$no_cache) {
        $keywords_in_db = get_hot_keyword_from_db(TAOBAOKE_AUTO_KEYWORDS);
        if (array_key_exists($keyword, $keywords_in_db)) {
            return $keywords_in_db[$keyword];
        }

        $keywords_in_db = get_hot_keyword_from_db(TAOBAOKE_HOT_KEYWORDS);
        if (array_key_exists($keyword, $keywords_in_db)) {
            return $keywords_in_db[$keyword];
        }
    }

    $taobaoke_api = new TaobaokeApi();
	$request = new TaobaokeGetSearchUrlRequest();
	$request->setKeyword($keyword);
	$request->setNick(var_get('nickname', 'wyattfang'));
	$request->setOuterCode('blog');

	$search_result = $taobaoke_api->getSearchUrl($request);
	if (null != $search_result && array_key_exists('taobaoke_item', $search_result)) {
        $search_url = $search_result['taobaoke_item']['keyword_click_url'];

        return $search_url;
    }

    return 'http://haibao.huoban.taobao.com/tms/topic.php?pid=' . var_get('pid') . '&eventid=101853'; //TODO ERROR HANDLING LOGIC CODE
}

function get_hot_keywords() {
    $json = taobaoke_cache_get('taobaoke_hot_products');
    if (isset($json)) {
        return Json::jsonDecode($json);
    }

    $api_url = API_URL . 'service/hotkeywords/';

    $keywords = null;
    $json_result = <<<JSONCODE
{ "totalCount": 34, "hotkeywords": [ "连衣裙", "女包", "凉鞋", "iPhone 4G", "诺基亚", "加湿器", "暖手宝", "口罩", "核桃", "长寿果", "帮宝适", "多美滋", "美的", "豆浆机", "电磁炉", "假发", "欧莱雅", "保湿", "蜜雪儿", "BB霜", "蒙奇奇", "毛衣链", "HTC", "艾莱依", "登山鞋", "艾莱依", "羽绒服", "杰克琼斯", "韩版", "Tata", "长靴", "百丽", "UGG", "打底衫", "毛衣" ] }
JSONCODE;

    try {
        $json_result = file_get_contents($api_url); 

        $keywords = Json::jsonDecode($json_result);
    }
    catch (Exception $e) {
        //TODO
        $keywords = Json::jsonDecode($json_result);
    }

    taobaoke_cache_set('taobaoke_hot_products', $json_result, '');

    return $keywords;
}

function auto_sync_activities() {
    $ac = get_activities();

    var_set('activities', $ac);
}

function get_activities_from_db() {
    return var_get('activities');
}

function get_activities() {
    $api_url = API_URL . 'service/promotions';

    $json_result = '';
    try {
        $json_result = file_get_contents($api_url);

        $json_decoded = Json::jsonDecode($json_result);

        return $json_decoded;
    }
    catch (Exception $e) {
        //TODO
    }
}
?>
