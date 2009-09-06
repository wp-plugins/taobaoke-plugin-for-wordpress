<?php
class ApiNoResultException extends Exception {
    public function __construct($message, $errcode, $api) {
        parent::__construct($message, $errcode);
        $this->api = $api;
    }
}
