<?php
/**
 * @var \Models\Users\Rank $rank
 * @var array $errors
 * @var \Models\Users\User $user
 */
?>
<main class="container-fluid">
    <div class="card">
        <div class="card-content">
            <p class="card-title">Modifier</p>
            <div class="divider"></div>
            <form method="POST" action="<?= $router->getFullUrl('pEditRank'); ?>" class="row">
                <?php if(isset($errors['global'])) { ?>
                    <p class="helper-text red-text center-align"><?= $errors['global']; ?></p>
                <?php } ?>
                <div class="input-field col s12">
                    <input id="rankName" type="text" name="rankName" value="<?= isset($_POST['rankName']) ? $_POST['rankName'] : $rank->getName(false); ?>" />
                    <label for="rankName">Nom du rang</label>
                    <?php if(isset($errors['rankName'])) { ?>
                    <p class="helper-text red-text"><?= $errors['rankName']; ?></p>
                    <?php } ?>
                </div>
                <div class="col s12 m6">
                    <div class="row">
                        <div class="col s2 input-field">
                            <div id="iconView"><?= $rank->getIcon(); ?></div>
                        </div>
                        <div class="col s10 input-field">
                            <input id="rankIcon" type="text" name="rankIcon" value="<?= isset($_POST['rankIcon']) ? $_POST['rankIcon'] : $rank->getIcon(false); ?>" />
                            <label for="rankIcon">Icône du rang</label>
                            <?php if(isset($errors['rankIcon'])) { ?>
                                <p class="helper-text red-text"><?= $errors['rankIcon']; ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6">
                    <div class="row">
                        <div class="col s2 input-field mt-10">
                            <div id="modelColor"></div>
                        </div>
                        <div class="col s10 input-field">
                            <input id="rankColor" type="text" name="rankColor" value="<?= isset($_POST['rankColor']) ? $_POST['rankColor'] : $rank->getColor(); ?>" />
                            <label for="rankColor">Couleur du rang ( Des couleurs claires sont conseillées )</label>
                            <?php if(isset($errors['rankColor'])) { ?>
                                <p class="helper-text red-text"><?= $errors['rankColor']; ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="center-align">
                    <input type="hidden" name="rankId" value="<?= $rank->getId(); ?>" />
                    <input type="hidden" name="CSRFToken" value="<?= $user->getCSRFToken(); ?>" />
                    <button class="btn btn-large waves-effect waves-light w-100" type="submit">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</main>