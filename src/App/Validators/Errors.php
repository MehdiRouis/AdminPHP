<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 24/01/19
 * Time: 15:57
 */

namespace App\Validators;

/**
 * Class Errors
 * @package App\Validators
 */
class Errors {

    /**
     * @var array
     */
    private $errors = [];

    /**
     * Errors constructor.
     */
    public function __construct() {

    }

    /**
     * @return array
     */
    public function getErrors(): array {
        return $this->errors;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setError($key, $value) {
        return $this->errors[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getError($key) {
        return $this->errors[$key];
    }

    public function __destruct() {

    }

}