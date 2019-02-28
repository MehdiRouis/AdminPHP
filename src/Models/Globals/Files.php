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
class Files {

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
        return isset($_FILES[$key]) && !empty($_FILES[$key]) ? $_FILES[$key] : false;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setValue($key, $value) {
        $_FILES[$key] = $value;
        return $_FILES[$key];
    }

    /**
     * @param false|string $key
     */
    public function deleteValue($key = false) {
        if ($key) {
            unset($_FILES[$key]);
        } else {
            unset($_FILES);
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getPathInfo($key) {
        return pathinfo($this->getValue($key)['name']);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getExtension($key) {
        return pathinfo($this->getValue($key)['name'], PATHINFO_EXTENSION);
    }

    /**
     * @param string $key
     * @return string
     */
    public function getMimeType($key) {
        return mime_content_type($this->getValue($key)['tmp_name']);
    }

    /**
     * @param string $key
     * @param string $path
     * @param bool|string $name
     * @return bool|string
     * @throws \Exception
     */
    public function secureUploadFile($key, $path, $name = false) {
        if(!$name) {
            $name = bin2hex(random_bytes(16)).'.'.$this->getExtension($key);
        }
        move_uploaded_file($this->getValue($key)['tmp_name'], $path . '/' . $name);
        return $name;
    }

    public function __destruct() {

    }

}