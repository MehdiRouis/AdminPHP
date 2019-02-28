<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */

namespace App\Routes;

use App\Protections\Security;

/**
 * Class Router
 * @package App\Routes
 */
class Router {

    /**
     * Stockage du $_GET[$url]
     * @var $url
     */
    private $url;

    /**
     * Liste des routes
     * @var array
     */
    private $routes = [];

    /**
     *
     * @var array
     */
    private $namedRoutes = [];

    /**
     *
     * @param string $url
     */
    public function __construct($url) {
        $this->url = isset($_GET[$url]) ? $_GET[$url] : '/';
    }

    /**
     * Ajouter une route avec la méthode GET
     * @param string $path
     * @param string|array $callable
     * @param null|string $name
     * @return Route
     */
    public function get($path, $callable, $name = null): Route {
        return $this->add($path, $callable, $name, 'GET');
    }

    /**
     * Ajouter une route avec la méthode POST
     * @param string $path
     * @param string|array $callable
     * @param null|string $name
     * @return Route
     */
    public function post($path, $callable, $name = null): Route {
        return $this->add($path, $callable, $name, 'POST');
    }

    /**
     * Englobe les méthodes post et get pour éviter la répétition
     * @param string $path
     * @param string|array $callable
     * @param null|string $name
     * @param string $method
     * @return Route
     */
    private function add($path, $callable, $name, $method): Route {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if (is_string($callable) && $name === null) {
            $this->namedRoutes[$callable] = $route;
        }
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    /**
     * Lancer l'execution d'un Controller si la route actuelle correspond.
     * @throws \Exception RouterExceptions
     */
    public function run() {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new RouterExceptions('REQUEST_METHOD does not exist.');
        }
        $match = false;
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            /** @var Route $route */
            if ($route->match($this->url)) {
                $route->call();
                $match = true;
            }
        }
        if (!$match) {
            if(isset($this->namedRoutes['default'])) {
                $this->safeLocalRedirect('default');
            } else {
                throw new RouterExceptions('No matching routes.');
            }
        }
    }

    /**
     * Récupération de la fonction de redirection locale de la classe Security.
     * @param string $link
     * @param array $params
     * @param bool $exit
     */
    private function safeLocalRedirect($link, $params = [], $exit = true) {
        $security = new Security();
        $security->safeLocalRedirect($link, $params, $exit);
    }

    /**
     * Obtenir le lien d'une route à partir du nom
     * @param string $name
     * @param array $params
     * @return string
     * @throws \Exception RouterExceptions
     */
    public function getUrl($name, $params = []): string {
        if (!isset($this->namedRoutes[$name])) {
            throw new RouterExceptions('No route found with this name.');
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }

    public function getActualRoute(): string {
        $actualRoute = false;
        foreach($this->namedRoutes as $name => $route) {
            if($route->isActualRoute()) {
                $actualRoute = $name;
            }
        }
        return $actualRoute;
    }

    /**
     * Obtenir le lien complet d'une route à partir de son nom
     * @param string $name
     * @param array $params
     * @return string
     * @throws \Exception RouterExceptions
     */
    public function getFullUrl($name, $params = []): string {
        return PROJECT_LINK . '/' . $this->getUrl($name, $params);
    }

}
