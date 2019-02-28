<?php


/**
 * AlivePHP - Simple micro-framework in PHP.
 * Je vous propose ce micro-framework pour les projets de petites tailles.
 * Niveau requis : Débutant - [[Amateur]] - Intermédiaire - Professionnel - Maître Yoda
 * Types de projets : [[Petit]] - [[Moyen]] - Grand - Très grands
 * Cette page est le cœur de l'application.
 */

/* |Definitions| */
setlocale(LC_TIME, 'fr_FR.utf8'); // Heure traduite en français
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT']); // Dossier local du VHOST
define('PROJECT_SOURCE', ''); // Dossier dans lequel le projet est ( à partir du PROJECT_ROOT )
define('PROJECT_LIBS', PROJECT_ROOT . PROJECT_SOURCE); // Dossier local + Dossier contenant le projet
define('PROJECT_LINK', 'http://adminawp' . PROJECT_SOURCE); // Lien externe du projet + Dossier contenant le projet
define('WEBSITE_LINK', 'http://alivewebproject');
define('WEBSITE_ROOT_FOLDER_NAME', 'AliveWebProject'); // Nom du dossier afin de pouvoir supprimer des fichiers du site principal.
define('PROJECT_NAME', 'AliveWebProject');
define('PROJECT_INITIALS', 'AWP');
/* [Appel des classes] */
require PROJECT_LIBS . '/vendor/autoload.php';

/* {Instance des classes} */
$router = new \App\Routes\Router('url');
$security = new \App\Protections\Security();

/* Redirection vers HTTPS */
/*
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $security->safeExternalRedirect($location);
}
*/

/* #Routes# */
require PROJECT_LIBS . '/init/routes.php';

/* ~Run~ */
$router->run();