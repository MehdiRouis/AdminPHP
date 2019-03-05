<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */

namespace Controllers;

/**
 * Class IndexController
 * @package Controllers
 */
class IndexController extends Controller {

    /**
     * @Route(name="home" path="/")
     * @throws \Exception
     */
    public function getHomepage() {
        $this->security->restrict(false);
        $this->render('index', ['pageName' => 'Accueil', 'headerTpl' => false, 'footerTpl' => false]);
    }

    /**
     * @Route(name="default" path="/404")
     * @throws \Exception
     */
    public function getNotFound() {
        $this->render('errors/404', ['pageName' => 'Page introuvable.']);
    }

    /**
     * @Route(name="logout" path="/account/logout")
     * @throws \Exception
     */
    public function getLogout() {
        $this->security->restrict();
        $this->dbauth->logOut();
    }

    /**
     * @Route(name="plogin" path="/")
     * @throws \Exception
     */
    public function postLogin() {
        $this->security->restrict(false);
        $errors = $this->dbauth->logIn('username', 'password');
        $this->render('index', ['pageName' => 'Accueil', 'headerTpl' => false, 'footerTpl' => false, 'errors' => $errors]);
    }

}
