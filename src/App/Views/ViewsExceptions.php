<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */

namespace App\Views;

/**
 * Class ViewsExceptions
 * @package App\Views
 */
class ViewsExceptions extends \Exception {

    /**
     * Affichage de l'exception
     * @param string $message
     * @throws \Exception
     */
    public function __construct($message) {
        parent::__construct($message);
    }

}
