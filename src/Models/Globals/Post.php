<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 24/01/19
 * Time: 07:57
 */

namespace Models\Globals;

/**
 * Class Post
 * @package Models\Globals
 */
class Post {

    /**
     * Post constructor.
     */
    public function __construct() {

    }

    /**
     * @param string $key
     * @return false|mixed
     */
    public function getValue($key) {
        return isset($_POST[$key]) && !empty($_POST[$key]) ? $_POST[$key] : false;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setValue($key, $value) {
        $_POST[$key] = $value;
        return $_POST[$key];
    }

    /**
     * @param false|string $key
     */
    public function deleteValue($key = false) {
        if ($key) {
            unset($_POST[$key]);
        } else {
            unset($_POST);
        }
    }

    public function __destruct() {

    }

}