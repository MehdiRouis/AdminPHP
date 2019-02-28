<?php
/**
 * @var array $errors
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title><?= PROJECT_NAME; ?><?= isset($pageName) ? ' - ' . $pageName : ''; ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,300,600,800,900" rel="stylesheet" type="text/css" />
    <link rel="icon" type="image/ico" href="<?= PROJECT_LINK; ?>/public/assets/img/favicon.png" />
    <link type="text/css" rel="stylesheet" href="<?= PROJECT_LINK; ?>/public/assets/import/Materialize/css/materialize.min.css"  media="screen" />
    <link type="text/css" rel="stylesheet" href="<?= PROJECT_LINK; ?>/public/assets/css/style.css" />
</head>
<body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu 1-column login-bg  blank-page blank-page" data-open="click" data-menu="vertical-modern-menu" data-col="1-column">
<div class="row">
    <div class="col s12">
        <div id="login-page" class="row">
            <div class="col s11 m8 l6 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
                <form class="login-form container" method="POST" action="<?= $router->getFullUrl('plogin') ?>">
                    <div class="row">
                        <div class="input-field col s12 center-align">
                            <p class="rem13">Connexion</p>
                            <div class="divider"></div>
                            <?php if(isset($errors['global'])) { ?>
                            <span class="helper-text red-text"><?= $errors['global']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row margin">
                        <div class="input-field col s12 xl6">
                            <i class="material-icons prefix pt-2">person_outline</i>
                            <input id="username" name="username" type="text">
                            <label for="username" class="center-align">Pseudo / email</label>
                            <?php if(isset($errors['username'])) { ?>
                                <span class="helper-text red-text"><?= $errors['username']; ?></span>
                            <?php } ?>
                        </div>
                        <div class="input-field col s12 xl6">
                            <i class="material-icons prefix pt-2">lock_outline</i>
                            <input id="password" name="password" type="password">
                            <label for="password">Mot de passe</label>
                            <?php if(isset($errors['password'])) { ?>
                                <span class="helper-text red-text"><?= $errors['password']; ?></span>
                            <?php } ?>
                        </div>
                        <div class="input-field col s12">
                            <button type="submit" class="btn btn-large waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">Se connecter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?= PROJECT_LINK; ?>/public/assets/import/jQuery/jquery-3.3.1.min.js"></script>
<script src="<?= PROJECT_LINK; ?>/public/assets/import/Materialize/js/materialize.min.js"></script>
<script src="<?= PROJECT_LINK; ?>/public/assets/import/SweetAlert/sweetalert.min.js"></script>
<script src="<?= PROJECT_LINK; ?>/public/assets/js/core.js"></script>
<?php foreach($scripts as $script) { ?>
    <script src="<?= PROJECT_LINK; ?>/public/assets/<?= $script; ?>"></script>
<?php } ?>
</body>
</html>