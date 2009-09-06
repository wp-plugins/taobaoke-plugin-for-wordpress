<?php
/*
 * Json util class uese to parse Json.
 */
class Json {
    /**
     * Decode function used to decode the json string.
     * @param string $json the json format string.
     * @param bool $assoc when TRUE, returned objects will be converted into associative arrays.
     */
    public static function jsonDecode($json, $assoc = true) {
        return json_decode($json, $assoc);
    }

    /**
     * Encode function used to encode the object&array to json string.
     * @param mixed $value, object or array which would be encoded.
     * @param Bitmask constisting of PHP_JSON_HEX_QUOT, PHP_JSON_HEX_TAG, PHP_JSON_HEX_AMP, PHP_JSON_HEX_APOS. Defaults to 0.
     */
    public static function jsonEncode($value, $options = NULL) {
        if (NULL != $options && is_bool($options)) {
            return json_encode($value, $options);
        }
        else {
            return json_encode($value);
        }
    }
}
?>
