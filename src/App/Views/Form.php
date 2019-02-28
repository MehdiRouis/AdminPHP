<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 24/01/19
 * Time: 16:38
 */

namespace App\Views;

use App\Routes\Router;
use Models\Globals\Post;
use Models\Users\User;

class Form {

    /**
     * @var \App\Routes\Router
     */
    private $router;

    /**
     * @var Post
     */
    private $post;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $html;

    /**
     * @var array
     */
    private $errors;

    /**
     * Form constructor.
     * @param array $errors
     * @param string $routeName
     * @param string $method
     * @param bool $csrf
     * @throws \Exception \App\Routes\RouterExceptions
     */
    public function __construct($errors, $routeName, $method = 'POST', $csrf = true, $extra = false) {
        $this->errors = $errors;
        $this->post = new Post();
        $this->router = $GLOBALS['router'];
        $this->method = $method;
        if(filter_var($routeName, FILTER_VALIDATE_URL)) {
            $this->route = $routeName;
        } else {
            $this->route = $this->getRouter()->getFullUrl($routeName);
        }
        $extra = $extra ? ' ' . $extra : '';
        $this->html = '<form class="row" method="' . $method . '" action="' . $this->route . '"' . $extra . '>';
        $user = new User();
        $this->addGlobalMessage();
        if($csrf) {
            $this->addSecurityToken('CSRFToken', $user->getCSRFToken());
        }
    }

    /**
     * @return \App\Routes\Router
     */
    private function getRouter(): Router {
        return $this->router;
    }

    /**
     * @return Post
     */
    private function getPost(): Post {
        return $this->post;
    }

    /**
     * @param string $html
     */
    public function addHTML($html) {
        $this->html .= "{$html}\n";
    }

    private function addGlobalMessage() {
        $error = isset($this->errors['global']) ? $this->errors['global'] : '';
        $this->addHTML('<p class="helper-text red-text center-align">' . $error . '</p>');
    }

    public function addLeftText($content) {
        $this->addHTML('<p class="left">' . $content . '</p>');
    }

    public function addRightText($content) {
        $this->addHTML('<p class="right">' . $content . '</p>');
    }

    /**
     * @param string $id
     * @param string $label
     * @param string $fieldClass
     * @param string $type
     * @param string $pattern
     * @param string $inputClass
     */
    public function addField($id, $label, $fieldClass = 'col s12', $type = 'text', $pattern = '', $inputClass = 'validate') {
        $fieldClass = 'input-field ' . $fieldClass;
        $inputClass = $inputClass === 'validate' ? $inputClass : 'validate '.$inputClass;
        $pattern = !empty($pattern) ? ' pattern="' . $pattern . '"' : '';
        $placeHolder = $type === 'date' || $type === 'time' ? ' placeholder=""' : '';
        $value = $this->getPost()->getValue($id) ? $this->getPost()->getValue($id) : '';
        $error = isset($this->errors[$id]) ? $this->errors[$id] : '';
        $this->addHTML('<div class="' . $fieldClass . '">');
        $this->addHTML('<input id="' . $id . '" name="' . $id . '" type="' . $type . '" value="' . $value . '"' . $placeHolder . ' class="' . $inputClass . '"' . $pattern . ' />');
        $this->addHTML('<label for="' . $id . '">' . $label . '</label>');
        $this->addHTML('<span class="helper-text red-text">' . $error . '</span>');
        $this->addHTML('</div>');
    }

    /**
     * @param string $id
     * @param string $label
     * @param array $content
     * @param string $fieldClass
     */
    public function addSelect($id, $label, $content = [], $fieldClass = 'col s12') {
        $fieldClass = 'input-field ' . $fieldClass;
        $error = isset($this->errors[$id]) ? $this->errors[$id] : '';
        $this->addHTML('<div class="' . $fieldClass . '">');
        $this->addHTML('<select id="' . $id . '" name="' . $id . '">');
        $this->addHTML('<option value="" disabled selected>Choisissez une option</option>');
        foreach($content as $value => $name) {
            $selected = $this->getPost()->getValue($id) == $value ? ' selected' : '';
            $this->addHTML('<option value="' . $value . '"' . $selected . '>' . $name . '</option>');
        }
        $this->addHTML('</select>');
        $this->addHTML('<label for="' . $id . '">' . $label . '</label>');
        $this->addHTML('<span class="helper-text red-text">' . $error . '</span>');
        $this->addHTML('</div>');
    }

    /**
     * @param string $captcha
     * @param string $id
     * @param string $label
     */
    public function addCaptcha($captcha, $id, $label = 'Recopiez le texte de l\'image.') {
        $value = $this->getPost()->getValue($id) ? $this->getPost()->getValue($id) : '';
        $error = isset($this->errors[$id]) ? $this->errors[$id] : '';
        $this->addHTML('<div class="input-field col s12 m4">');
        $this->addHTML($captcha);
        $this->addHTML('</div>');
        $this->addHTML('<div class="input-field col s12 m8">');
        $this->addHTML('<input id="' . $id . '" name="' . $id . '" type="text" value="' . $value . '" class="validate" />');
        $this->addHTML('<label for="' . $id . '">' . $label . '</label>');
        $this->addHTML('<span class="helper-text red-text">' . $error . '</span>');
        $this->addHTML('</div>');
    }

    public function addHiddenValue($id, $value) {
        $this->addHTML('<input id="' . $id . '" name="' . $id . '" type="hidden" value="' . $value . '" />');
    }

    /**
     * @param string $id
     * @param string $label
     * @param bool $class
     * @param bool|string $pattern
     */
    public function addTextarea($id, $label, $class = false, $pattern = false) {
        $value = $this->getPost()->getValue($id) ? $this->getPost()->getValue($id) : $label;
        $error = isset($this->errors[$id]) ? $this->errors[$id] : '';
        $pattern = $pattern ? 'pattern="' . $pattern . '"' : '';
        $class = $class ? 'materialize-textarea validate ' . $class : 'materialize-textarea validate';
        $this->addHTML('<div class="input-field col s12">');
        $this->addHTML('<textarea id="' . $id . '" name="' . $id . '" class="' . $class . '" ' . $pattern . ' placeholder="' . $value . '"></textarea>');
        $this->addHTML('<label for="' . $id . '">' . $value . '</label>');
        $this->addHTML('<p class="helper-text red-text">' . $error . '</p>');
        $this->addHTML('</div>');
    }

    /**
     * @param string $id
     * @param string $value
     */
    public function addSecurityToken($id, $value) {
        $this->addHTML('<input id="' . $id . '" name="' . $id . '" type="hidden" value="' . $value . '" />');
    }
    /**
     * @param string $label
     * @param string $class
     */
    public function addSubmit($label = 'Envoyer', $class = 'btn btn-large') {
        $class = $class === 'btn btn-large' ? $class : 'btn ' . $class;
        $this->addHTML('<button type="submit" class="' . $class . '">' . $label . '</button>');
    }

    /**
     * @echo string
     */
    public function parse() {
        echo $this->html;
        echo '</form>';
    }

    public function __destruct() {

    }

}