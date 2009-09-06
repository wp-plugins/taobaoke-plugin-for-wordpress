<?php
/**
 * Wrapper class includes common functions.
 */
class Common {
    /**
     * Redirects the page.
     * @param string $page target page location
     * @return void
     */
    public static function gotoPage($page) {
        header('Location: ' . $page);
        die;
    }

    /**
     * Generates a unique id with all characters uppercase.
     * @return string the generated guid with 32 characters
     */
    public static function generateGuid() {
        $id = '';

        if (function_exists('com_create_guid')) { //if exists function 'com_create_guid', use it directly
            $id = com_create_guid();
        }
        else { //if not, call function 'md5' and 'uniqid' to generate
            mt_srand((double) microtime() * 10000);
            $id = strtoupper(md5(uniqid(rand(), TRUE)));
        }
        $id = str_replace('{', '', $id);
        $id = str_replace('}', '', $id);
        $id = str_replace('-', '', $id);

        return $id;
    }

    /**
     * Filters an array, unsets the member whose value is NULL
     * @param array $array the reference to array that will be filtered
     * @return array array has been filtered
     */
    public static function filterArray(&$array) {
        foreach ($array as $key => $value) {
            if (is_null($value) || empty($value) || '' === $value) { //clear the unset field
                unset($array[$key]);
            }
        }
    }

    /**
     * Gets the remote machine's IP address.
     * @return string client side's IP address
     */
    public static function getRemoteIp($to_long = FALSE) {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

        return ($to_long) ? sprintf('%u', ip2long($ip)) : $ip;
    }

    /**
     * Gets current script name.
     * @return string current URL's script name
     */
    public static function getScriptName() {
        $php_self = $_SERVER['PHP_SELF'];

        return substr($php_self, strrpos($php_self, '/') + 1);
    }

    /**
     * Gets current script virtual path.
     * @return string current script's virtual path
     */
    public static function getScriptVirtualPath() {
        $php_self = $_SERVER['PHP_SELF'];

        return substr($php_self, 0, strrpos($php_self, '/'));
    }

    /**
     * Gets query string.
     * @return string the query string (after '?') in current URL
     */
    public static function getQueryString() {
        return isset($_SERVER['QUERY_STRING']) ? urldecode($_SERVER['QUERY_STRING']) : NULL;
    }

    /**
     * Gets the 'HTTP_FERERER' for $_SERVER.
     * @return string the refer page for current location
     */
    public static function getReferPage() {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
    }

   /**
     * Returns the date after input days.
     * @param string $date
     * @param int $interval
     * @return string next day
     */
    public static function getNextDate($date, $interval) {
        if (0 == $interval) {
            return $date;
        }
        $tmp_date = explode('-', $date);

        return date('Y-m-d', mktime(0, 0, 0, $tmp_date[1], $tmp_date[2] + $interval, $tmp_date[0]));
    }

    /**
     * Transfers date array to date string.
     * @param array $date_array date array
     * @return string date string
     */
    public static function dateArray2String ($date_array) {
        $dates_string = '';
        $last_date = NULL;
        $i = 0;
        $length = count($date_array);
        foreach ($date_array as $date) {
            if ('' == $dates_string) {
                $dates_string .= $date;
                $last_date = $date;
            }
            else {
                $next_date = Common::getNextDate($last_date, 1);
                if ($next_date != $date) {
                    $dates_string .= ':' . $last_date;
                    $dates_string .= ',' . $date;
                }
                $last_date = $date;
            }
            if ($length == ($i + 1)) {
                $dates_string .= ':' . $date;
            }
            $i++;
        }

        return $dates_string;
    }

    /**
     * Wraps the long string into lines by adding '<br/>'.
     * @param string $string the original string
     * @param int $line_length the length for each line
     * @param boolean $break_all break the string at the exact character or word boundary
     * @return string
     */
    public static function wrapString($string, $line_length = 25, $break_all = TRUE) {
        $temp_wrap = (wordwrap($string, $line_length, '<br />', $break_all));
        $temp_wrap = explode('<br />', $temp_wrap);
        foreach ($temp_wrap as $key => $piece) {
            $temp_wrap[$key] = htmlspecialchars($piece);
        }

        return implode('<br />', $temp_wrap);
    }

    /**
     * Truncates the string with specified string(such as '...') by specified length.
     * @param string $string the original string
     * @param int $length the length to truncate
     * @param string $tail the specifed string as tail
     * @return string
     */
    public static function truncateString($string, $length = 25, $etc = '...') {
        $result = '';
		$string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
		$strlen = strlen($string);

		for ($i = 0;(($i < $strlen) && ($length > 0)); $i++) {
			if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
				if ($length < 1.0) {
					break;
				}

				$result .= substr($string, $i, $number);
				$length -= 1.0;

				$i += $number -1;
			}
			else {
				$result .= substr($string, $i, 1);
				$length -= 0.5;
			}
		}

		$result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

		if ($i < $strlen) {
			$result .= $etc;
		}

		return $result;
    }

    /**
     * Checks if current browser if FireFox.
     * @return boolean
     */
    public static function isFireFox() {
        return (bool) strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'firefox');
    }

    /**
     * Stores the POST data into specific session index.
     * @param string $key the session index
     * @return void
     */
    public static function storePostData($key) {
        if (0 < count($_POST)) {
            $_SESSION[$key] = $_POST;
        }
    }

    /**
     * Strip the 'https://' or 'http://' in URL at the beginning.
     * @param string $url the URL to be stripped
     * @return string
     */
    public static function stripHttpPrefix($url) {
        if (0 === strpos($url, 'http://')) {
            $url = substr_replace($url, NULL, 0, 7);
        }
        else if (0 === strpos($url, 'https://')) {
            $url = substr_replace($url, NULL, 0, 8);
        }

        return $url;
    }

    /**
     * Replaces the enter key of a string to <br>.
     * @param string $string
     * @return string
     */
    public static function replaceEnterKeyAsBr($string) {
        $string = str_replace("\r\n", "\n", $string);
        $string = str_replace("\r", "\n", $string);
        $string = str_replace("\n", '<br/>', $string);

        return $string;
    }

    /**
     * Checks query string.
     * @return boolean
     */
    public static function isEmptyQuery() {
        $query_array = NULL;
        $params = Common::getQueryString();
        parse_str($params, $query_array);
        unset($query_array['sid']);

        return empty($query_array);
    }

    /**
     * 获得字符串的长度,兼容英文和中文.一个中文字符或一个英文字符都会返回1
     * $param string
     * @return int
     */
    public static function getCharacterNum($string) {
    	return mb_strlen($string, 'UTF8');
    }

    /**
     * 获得传入的时间戳当天的0：00的时间戳
     * @param int UNIX时间戳
     * @return int 当天0:00的UNIX时间戳
     */
    public static function beginOfDay($timestamp) {
        $month = (int)date('m', $timestamp);
        $day = (int)date('d', $timestamp);
        $year = (int)date('Y', $timestamp);
        return mktime(0, 0 , 0, $month, $day, $year);
    }

    /** 获得传入的时间戳本周第一天的0：00的时间戳
     * @param int UNIX时间戳
     * @return int 本周第一天0:00的UNIX时间戳
     */
    public static function beginOfWeek($timestamp) {
        $month = (int)date('m', $timestamp);
        $day = (int)date('d', $timestamp);
        $year = (int)date('Y', $timestamp);
        $week_day = (int)date('w', $timestamp);
        return mktime(0, 0 , 0, $month, $day-$week_day, $year);
    }

    /**
     * 获得传入的时间戳当月第一天的0：00的时间戳
     * @param int UNIX时间戳
     * @return int 当月第一天0:00的UNIX时间戳
     */
    public static function beginOfMonth($timestamp) {
        $month = (int)date('m', $timestamp);
        $day = (int)date('d', $timestamp);
        $year = (int)date('Y', $timestamp);
        return mktime(0, 0 , 0, $month, 1, $year);
    }

    /**
     * 获得传入的时间戳当年第一天的0：00的时间戳
     * @param int UNIX时间戳
     * @return int 当当年第一天0:00的UNIX时间戳
     */
    public static function beginOfYear($timestamp) {
        $month = (int)date('m', $timestamp);
        $day = (int)date('d', $timestamp);
        $year = (int)date('Y', $timestamp);
        return mktime(0, 0 , 0, 1, 1, $year);
    }
}
?>
