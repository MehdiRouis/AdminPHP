<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */

namespace App\Views;

use App\Cache\Cache;

/**
 * Class View
 * @package App\Views
 */
class View {

    /**
     * Variables à extraires dans les vues
     * @var array
     */
    private $data = [];

    /**
     * Chemin de la vue
     * @var bool|string
     */
    private $render = false;

    /**
     * @var \App\Routes\Router
     */
    private $router;

    /**
     * @param string $templateFile Nom du fichier à rendre
     * @throws \Exception ViewsExceptions
     */
    public function __construct($templateFile) {
        $this->router = $GLOBALS['router'];
        $templateFile = str_replace('.php', '', $templateFile);
        $file = PROJECT_LIBS . '/public/views/' . $templateFile . '.php';
        if (file_exists($file)) {
            $this->render = $file;
        } else {
            throw new ViewsExceptions('Erreur... La template : ' . $file . ' est introuvable.');
        }
    }

    /**
     * Assigner les variables à extraire dans la vue
     * @param array $dataArray
     */
    public function assign($dataArray = []) {
        $this->data = $dataArray;
    }

    /**
     * Extraction des variables de la vue ainsi que des requires pour les templates et rendues des vues
     * @throws \Exception ViewsExceptions
     */
    public function __destruct() {
        extract($this->data);
        if ($this->render) {
            require $this->render;
        }
    }

}