<?php
/**
 * @var \App\Views\Navbar $navbar
 * @var array $news
 * @var string $pageName
 * @var \Models\Authentication\DBAuth $auth
 * @var \Models\Users\User $user
 * @var \App\Routes\Router $router
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title><?= PROJECT_NAME; ?><?= isset($pageName) ? ' - ' . $pageName : ''; ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.0/css/all.css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,300,600,800,900" rel="stylesheet" type="text/css" />
    <link rel="icon" type="image/ico" href="<?= PROJECT_LINK; ?>/public/assets/img/favicon.png" />
    <link type="text/css" rel="stylesheet" href="<?= PROJECT_LINK; ?>/public/assets/import/Materialize/css/materialize.min.css"  media="screen" />
    <link type="text/css" rel="stylesheet" href="<?= PROJECT_LINK; ?>/public/assets/css/style.css" />
</head>
<body class="grey lighten-2">
<div id="home">
    <div class="parallax" data-img="<?= PROJECT_LINK; ?>/public/assets/img/parallax/header.jpg"></div>
</div>
<header>
    <nav id="navbar">
        <div class="nav-wrapper gradient-45deg-red-pink">
            <a href="<?= $auth->isLogged() ? $router->getFullUrl('dashboard') : $router->getFullUrl('home'); ?>"><i class="fas fa-cloud"></i></a>
            <a href="<?= $auth->isLogged() ? $router->getFullUrl('dashboard') : $router->getFullUrl('home'); ?>" class="brand-logo"><?= PROJECT_INITIALS; ?></a>
            <ul class="right center-align">
                <li><a id="menuSideNav" data-target="slide-out" class="right sidenav-trigger"><i class="material-icons">menu</i></a></li>
            </ul>
        </div>
    </nav>
</header>
    <?php
    $navbar->addUserView($user);
    $navbar->add('dashboard', 'ACCUEIL', 'fas fa-home');
    $navbar->addDropDown('PERMISSIONS',
        [
                ['label' => 'LISTE DES PERMISSIONS', 'route' => 'permissions', 'icon' => 'far fa-hand-point-right', 'permission' => 'edit-permissions'],
                ['label' => 'LISTE DES RANGS', 'route' => 'ranks', 'icon' => 'fas fa-users', 'permission' => 'view-ranks']
        ]);
    $navbar->addDropDown('UTILISATEURS',
        [
                ['label' => 'LISTE DES UTILISATEURS', 'route' => 'allUsers', 'icon' => 'fas fa-users', 'permission' => 'view-users']
        ]);
    $navbar->addDropDown('PROJETS',
        [
                ['label' => 'LISTE DES PROJETS', 'route' => 'projects', 'icon' => 'fas fa-project-diagram', 'permission' => 'view-projects']
        ]);
    $navbar->add('logout', 'SE DÉCONNECTER', 'fas fa-sign-out-alt', 'logout');
    $navbar->parse();
    ?>
