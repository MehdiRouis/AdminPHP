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
use Models\Users\User;

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

    /**
     * @var User
     */
    private $user;

    public function __construct() {
        $this->router = $GLOBALS['router'];
        $this->user = new User();
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
     * @param bool|string $icon
     * @param bool|string $classLi
     * @param array $linkParams
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

    public function addDropDown($label, $content = [], $icon = false) {
        $actualActive = '';
        $style = '';
        for($i = 0; $i < count($content); $i++) {
            if($this->getRouter()->getActualRoute() === $content[$i]['route']) {
                $actualActive = ' active';
                $style = ' style="display : block;"';
            }
        }
        $icon = $icon ? '<i class="' . $icon . '"></i> ' : '<i class="material-icons">arrow_drop_down</i> ';
        $this->addHTML('<li class="no-padding' . $actualActive . '">');
        $this->addHTML('<ul class="collapsible collapsible-accordion">');
        $this->addHTML('<li>');
        $this->addHTML('<a class="collapsible-header">' . $icon . $label . '</a>');
        $this->addHTML('<div class="collapsible-body' . $actualActive . '"' . $style . '>');
        $this->addHTML('<ul>');
        foreach($content as $name => $options) {
            $routeName = $options['route'];
            $permission = isset($options['permission']) ? $this->user->hasRight($options['permission']) : true;
            if ($permission) {
                $liIcon = isset($options['icon']) ? '<i class="' . $options['icon'] . '"></i> ' : '';
                $classActive = $this->getRouter()->getActualRoute() === $routeName ? ' active' : '';
                $this->addHTML('<li class="item' . $classActive . '"><a class="item" href="' . $this->getRouter()->getFullUrl($routeName) . '">' . $liIcon . $options['label'] . '</a></li>');
            }
        }
        $this->addHTML('</ul>');
        $this->addHTML('</div>');
        $this->addHTML('</li>');
        $this->addHTML('</ul>');
        $this->addHTML('</li>');

    }

    public function parse() {
        $this->addHTML('</ul>');
        echo $this->html;
    }

    public function __destruct() {

    }
}
