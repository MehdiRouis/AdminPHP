<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 16:33
 */

namespace App\Views;

use App\Routes\Router;

class Navbar
{
    /**
     * @var \App\Routes\Router
     */
    private $router;

    /**
     * @var string
     */
    private $html;

    public function __construct() {
        $this->router = $GLOBALS['router'];
        $this->html = '<ul id="slide-out" class="sidenav sidenav-fixed">';
    }

    public function getRouter(): Router {
        return $this->router;
    }

    private function addHTML($html) {
        $this->html .= "{$html}\n";
    }

    /**
     * @param \Models\Users\User $user
     */
    public function addUserView($user) {
        $this->addHTML('<li>');
        $this->addHTML('<div class="user-view">');
        $this->addHTML('<div class="background">');
        $this->addHTML('<img src="' . PROJECT_LINK  . '/public/assets/img/navbar/background.jpg" alt="Office background" />');
        $this->addHTML('</div>');
        $this->addHTML('<a href=""><span class="circle white darken-1 black-text hoverable avatar">' . $user->getInitialFirstName() . '</span></a>');
        $this->addHTML('<a href=""><span class="white-text name right-align">' . $user->getFullName() . '</span></a>');
        $this->addHTML('<a href=""><span class="white-text email right-align">' . $user->getEmail() . '</span></a>');
        $this->addHTML('</div>');
        $this->addHTML('</li>');
    }

    /**
     * @param string $routeName
     * @param string $label
     * @param bool $icon
     * @throws \Exception \App\Routes\RouterExceptions
     */
    public function add($routeName, $label, $icon = false, $classLi = false, $linkParams = []) {
        $extension = explode('#', $routeName);
        $routeExtension = '';
        if(count($extension) === 2) {
            $routeName = $extension[0];
            $routeExtension = '#' . $extension[1];
        }
        $icon = $icon ? '<i class="' . $icon . '"></i> ' : '';
        $classActiveLi = $classLi ? 'active ' . $classLi : 'active';
        $classLi = $classLi ? ' class="' . $classLi . '"' : '';
        $active = $this->getRouter()->getActualRoute() === $routeName ? ' class="' . $classActiveLi . '"' : $classLi;
        $this->addHTML('<li' . $active . '><a href="' . $this->getRouter()->getFullUrl($routeName, $linkParams) . $routeExtension . '">' . $icon . $label . '</a></li>');
    }

    /**
     * @param string $link
     * @param string $label
     * @param bool|string $icon
     * @param bool|string $classLi
     */
    public function addWithLink($link, $label, $icon = false, $classLi = false) {
        $icon = $icon ? '<i class="' . $icon . '"></i> ' : '';
        $classLi = $classLi ? ' class="' . $classLi . '"' : '';
        $this->addHTML('<li' . $classLi . '><a href="' . $link . '">' . $icon . $label . '</a></li>');
    }

    public function parse() {
        $this->addHTML('</ul>');
        echo $this->html;
    }

    public function __destruct() {

    }
}