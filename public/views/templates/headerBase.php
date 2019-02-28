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
    if($user->hasRight('edit-permissions')) {
        $navbar->add('permissions', 'PERMISSIONS', 'far fa-hand-point-right');
    }
    if($user->hasRight('view-ranks')) {
        $navbar->add('ranks', 'RANGS', 'fas fa-users');
    }
    if($user->hasRight('view-projects')) {
        $navbar->add('projects', 'PROJETS', 'fas fa-project-diagram');
    }
    if($user->hasRight('view-users')) {
        $navbar->add('allUsers', 'UTILISATEURS', 'fas fa-users');
    }
    $navbar->add('logout', 'SE DÉCONNECTER', 'fas fa-sign-out-alt', 'logout');
    $navbar->parse();
    ?>
