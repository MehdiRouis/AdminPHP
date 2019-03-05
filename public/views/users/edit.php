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
            <form action="<?= $router->getFullUrl('pEditUser'); ?>" method="POST" class="row">
                <?php if(isset($errors['global'])) { ?>
                <div class="center-align">
                    <p class="helper-text red-text"><?= $errors['global']; ?></p>
                </div>
                <?php } ?>
                <div class="input-field col s12 m4">
                    <input id="userName" type="text" name="userName" value="<?= isset($_POST['userName']) ? $_POST['userName'] : $userEdit->getUserName(); ?>" />
                    <label for="userName">Pseudonyme</label>
                    <?php if(isset($errors['userName'])) { ?>
                        <p class="helper-text red-text center-align"><?= $errors['userName']; ?></p>
                    <?php } ?>
                </div>
                <div class="input-field col s12 m4">
                    <input id="lastName" type="text" name="lastName" value="<?= isset($_POST['lastName']) ? $_POST['lastName'] : $userEdit->getLastName(); ?>" />
                    <label for="lastName">Nom</label>
                    <?php if(isset($errors['lastName'])) { ?>
                        <p class="helper-text red-text center-align"><?= $errors['lastName']; ?></p>
                    <?php } ?>
                </div>
                <div class="input-field col s12 m4">
                    <input id="firstName" type="text" name="firstName" value="<?= isset($_POST['firstName']) ? $_POST['firstName'] : $userEdit->getFirstName(); ?>" />
                    <label for="firstName">Prénom</label>
                    <?php if(isset($errors['firstName'])) { ?>
                        <p class="helper-text red-text center-align"><?= $errors['firstName']; ?></p>
                    <?php } ?>
                </div>
                <div class="input-field col s12 m4">
                    <select id="rankId" name="rankId">
                        <?php foreach($ranks as $rank) { /** @var \Models\Users\Rank $rank */ ?>
                            <option value="<?= $rank->getId(); ?>"<?= $rank->getId() === $userEdit->getRank()->getId() ? ' selected' : ''; ?>><?= $rank->getName(false); ?></option>
                        <?php } ?>
                    </select>
                    <label for="rankId">Rang</label>
                    <?php if(isset($errors['rankId'])) { ?>
                        <p class="helper-text red-text center-align"><?= $errors['rankId']; ?></p>
                    <?php } ?>
                </div>
                <div class="input-field col s12 m4">
                    <input id="email" type="email" name="email" value="<?= isset($_POST['email']) ? $_POST['email'] : $userEdit->getEmail(); ?>" />
                    <label for="email">Rang</label>
                    <?php if(isset($errors['email'])) { ?>
                        <p class="helper-text red-text center-align"><?= $errors['email']; ?></p>
                    <?php } ?>
                </div>
                <div class="input-field col s12 m4">
                    <input id="phoneNumber" type="text" name="phoneNumber" value="<?= isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : $userEdit->getPhoneNumber(); ?>" />
                    <label for="phoneNumber">Numéro de téléphone</label>
                    <?php if(isset($errors['phoneNumber'])) { ?>
                        <p class="helper-text red-text center-align"><?= $errors['phoneNumber']; ?></p>
                    <?php } ?>
                </div>
                <div class="input-field col s12 m4">
                    <input id="birthDay" type="date" name="birthDay" value="<?= isset($_POST['birthDay']) ? $_POST['birthDay'] : $userEdit->getBirthDay('Y-m-d'); ?>" />
                    <label for="birthDay">Date de naissance</label>
                    <?php if(isset($errors['birthDay'])) { ?>
                        <p class="helper-text red-text center-align"><?= $errors['birthDay']; ?></p>
                    <?php } ?>
                </div>
                <div class="input-field col s12 m4">
                    <input id="shopPoint" type="number" name="shopPoint" value="<?= isset($_POST['shopPoint']) ? $_POST['shopPoint'] : $userEdit->getShopPoints(); ?>" />
                    <label for="shopPoint">Points boutique</label>
                    <?php if(isset($errors['shopPoint'])) { ?>
                        <p class="helper-text red-text center-align"><?= $errors['shopPoint']; ?></p>
                    <?php } ?>
                </div>
                <div class="input-field col s12 m4">
                    <select id="profileType" name="profileType">
                        <option value="public"<?= $userEdit->getProfileType() === 'public' ? ' selected' : ''; ?>>Publique</option>
                        <option value="private"<?= $userEdit->getProfileType() === 'private' ? ' selected' : ''; ?>>Privé</option>
                    </select>
                    <label for="profileType">Type de profil</label>
                    <?php if(isset($errors['profileType'])) { ?>
                        <p class="helper-text red-text center-align"><?= $errors['profileType']; ?></p>
                    <?php } ?>
                </div>
                <input type="hidden" name="CSRFToken" value="<?= $user->getCSRFToken(); ?>" />
                <input type="hidden" name="userId" value="<?= $userEdit->getId(); ?>" />
                <div class="row">
                    <div class="col s12 m6">
                        <a href="<?= $router->getFullUrl('editUserPassword', ['id' => $userEdit->getId()]); ?>" class="btn btn-large waves-effect waves-light grey white-text col s12">Modifier le mot de passe</a>
                    </div>
                    <div class="col s12 m6">
                        <button type="submit" class="btn btn-large waves-effect waves-light pink white-text col s12">Valider</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>