<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */

namespace App\Routes;

/**
 * Class Route
 * @package App\Routes
 */
class Route {

    /**
     * URL d'accès à la page
     * @var string
     */
    private $path;

    /**
     * NomDeLaClasseController#NomDeLaMéthodeDuController
     * @var string|array
     */
    private $callable;

    /**
     * Paramètres de la méthode du Controller
     * @var array
     */
    private $matches = [];

    /**
     * Contenu des paramètres
     * @var array
     */
    private $params = [];

    /**
     * @var bool|string
     */
    private $actualRoute = false;

    /**
     * Stockage des données dans les attributs
     * @param string $path
     * @param string|array $callable
     */
    public function __construct($path, $callable) {

        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    /**
     * Ajouter un paramètre de méthode GET ( ?id=3 par exemple )
     * @param string $param
     * @param string $regex
     * @return $this
     */
    public function with($param, $regex): Route {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }

    /**
     * Vérifier le contenu de l'url ( REGEX ) afin de savoir s'il y a des paramètres à prendre en compte
     * @param string $url
     * @return bool
     */
    public function match($url): bool {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = '#^' . $path . '$#i';
        $matches = [];
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    /**
     * Vérification des paramètres de l'URL ( ?id=3 par exemple )
     * Si des paramètres existent, ils les retournent.
     * @param array $match
     * @return string
     */
    private function paramMatch($match): string {
        if (isset($this->params[$match[1]])) {
            return "({$this->params[$match[1]]})";
        }
        return '([^/]+)';
    }

    /**
     * @return bool|string
     */
    public function getActualRoute(): string {
        return $this->actualRoute;
    }

    /**
     * @param bool$value
     * @return bool
     */
    public function setActualRoute($value): bool {
        return $this->actualRoute = $value;
    }

    /**
     * @return bool
     */
    public function isActualRoute(): string {
        return $this->getActualRoute();
    }

    /**
     * Instancie la classe du Controller et fait appel à la méthode de la page.
     * @return mixed
     */
    public function call() {
        if (is_string($this->callable)) {
            $params = explode('#', $this->callable);
            $controller = '\Controllers\\' . $params[0] . 'Controller';
            $controller = new $controller();
            $this->setActualRoute(true);
            return call_user_func_array([$controller, $params[1]], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }

    /**
     * Récupérer le lien avec des paramètres prédéfinis ( ?id=3 => /3 )
     * @param array $params
     * @return string
     */
    public function getUrl($params): string {
        $path = $this->path;
        foreach ($params as $k => $v) {
            $path = str_replace(':' . $k, $v, $path);
        }
        return $path;
    }

    public function __destruct() {
        
    }

}
