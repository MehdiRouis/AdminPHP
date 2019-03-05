<?php
/**
 * @var \Models\Users\User $userEdit
 * @var \Models\Users\User $user
 * @var array $ranks
 * @var array $errors
 */
?>
<main class="container-fluid">
    <?php if(isset($_GET['action'])) { ?>
        <div class="card gradient-45deg-green-teal">
            <div class="card-content center-align white-text">
                <p class="card-title">Succès!</p>
                <div class="divider"></div>
                <p>Le compte a été modifié avec succès!</p>
            </div>
        </div>
    <?php } ?>
    <div class="card">
        <div class="card-content">
            <p class="card-title">Édition du compte : <?= $userEdit->getUserName(); ?> - <?= $userEdit->getFullName(); ?></p>
            <div class="divider"></div>
            <form action="<?= $router->getFullUrl('pEditUserPassword'); ?>" method="POST" class="row">
                <?php if(isset($errors['global'])) { ?>
                    <div class="center-align">
                        <p class="helper-text red-text"><?= $errors['global']; ?></p>
                    </div>
                <?php } ?>
                <div class="input-field col s12 m6">
                    <input id="newPassword" type="password" name="newPassword" placeholder="•••••••••" />
                    <label for="newPassword">Nouveau mot de passe</label>
                    <?php if(isset($errors['shopPoint'])) { ?>
                        <p class="helper-text red-text center-align"><?= $errors['shopPoint']; ?></p>
                    <?php } ?>
                </div>
                <div class="input-field col s12 m6">
                    <input id="reNewPassword" type="password" name="reNewPassword" placeholder="•••••••••" />
                    <label for="reNewPassword">Retapez le mot de passe</label>
                    <?php if(isset($errors['shopPoint'])) { ?>
                        <p class="helper-text red-text center-align"><?= $errors['reNewPassword']; ?></p>
                    <?php } ?>
                </div>
                <input type="hidden" name="CSRFToken" value="<?= $user->getCSRFToken(); ?>" />
                <input type="hidden" name="userId" value="<?= $userEdit->getId(); ?>" />
                <div class="row">
                    <div class="col s12 m6">
                        <a href="<?= $router->getFullUrl('editUser', ['id' => $userEdit->getId()]); ?>" class="btn btn-large waves-effect waves-light grey white-text col s12">Modifier l'utilisateur</a>
                    </div>
                    <div class="col s12 m6">
                        <button type="submit" class="btn btn-large waves-effect waves-light pink white-text col s12">Valider</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>