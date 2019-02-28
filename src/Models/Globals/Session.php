<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 17:59
 */

namespace Models\Globals;


class Session {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @param string|int $key
     * @return bool
     */
    public function getValue($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }

    /**
     * @param string|bool $key
     * @param mixed $value
     * @return mixed
     */
    public function setValue($key, $value) {
        $_SESSION[$key] = $value;
        return $_SESSION[$key];
    }

    /**
     * @param string|int $key
     * @return bool
     */
    public function existValue($key): bool {
        return isset($_SESSION[$key]);
    }

    /**
     * @param bool|string $key
     */
    public function deleteValue($key = false)
    {
        if ($key) {
            unset($_SESSION[$key]);
        } else {
            unset($_SESSION);
        }
    }
}