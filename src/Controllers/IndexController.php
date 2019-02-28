<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */

namespace Controllers;

use Models\Articles\Article;
use Models\Users\Rank;
use Models\Users\User;

/**
 * Class IndexController
 * @package Controllers
 */
class IndexController extends Controller {

    public function getHomepage() {
        $this->security->restrict(false);
        $this->render('index', ['pageName' => 'Accueil', 'headerTpl' => false, 'footerTpl' => false]);
    }

    public function getNotFound() {
        $this->render('errors/404', ['pageName' => 'Page introuvable.']);
    }

    public function getLogout() {
        $this->security->restrict();
        $this->dbauth->logOut();
    }

    public function postLogin() {
        $this->security->restrict(false);
        $errors = $this->dbauth->logIn('username', 'password');
        $this->render('index', ['pageName' => 'Accueil', 'headerTpl' => false, 'footerTpl' => false, 'errors' => $errors]);
    }

}
