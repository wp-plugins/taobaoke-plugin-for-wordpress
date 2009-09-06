<?php
/**
 * Provides the funcitonalities to manage get data.
 */
class GetManager {
    /**
     * An array for storing post data.
     * @var array
     */
    private $get_data = array ();

    /**
     * Gets the $_GET array.
     */
    public function __construct() {
        $this->get_data = $_GET;
    }

    /**
     * Returns the get data value by field name.
     * @param string $name get data index name
     * @return mixed string if the value exists, or NULL on failure
     */
    public function __get($name) {
        $value = isset($this->get_data[$name]) ? $this->get_data[$name] : NULL;

        return $value;
    }
}