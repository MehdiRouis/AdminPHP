<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */

namespace App\Routes;

/**
 * Class RouterExceptions
 * @package App\Routes
 */
class RouterExceptions extends \Exception {

    /**
     * Affichage des erreurs liés aux routes
     * @param string $message
     * @throws \Exception
     */
    public function __construct($message) {
        parent::__construct($message);
    }

    public function __destruct() {

    }

}
