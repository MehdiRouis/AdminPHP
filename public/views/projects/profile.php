<?php
/**
 * Created by PhpStorm.
 * User: stuuf
 * Date: 25/02/2019
 * Time: 19:36
 */
/**
 * @var \Models\Projects\Project $project
 * @var \Models\Users\User $user
 * @var array $rights
 */
?>
<main class="container-fluid">
    <div class="row">
        <div class="col s12 m4">
            <div class="card">
                <div class="card-image">
                    <img class="responsive-img" src="<?= $project->getCreatedBy()->getProfileBanner(); ?>" alt="user background" />
                    <p class="card-image-title"><?= $project->getCreatedBy()->getFullName(); ?><br /><?= $project->getCreatedBy()->getEmail(); ?></p>
                </div>
                <div class="card-content center-align">
                    <p><?= $project->getCreatedBy()->getRank()->getName(); ?></p>
                    <div class="divider mt-20"></div>

                </div>
            </div>
        </div>
        <?php if($user->hasRight('delete-projects')) { ?>
        <div id="deleteModal" class="modal">
            <div class="modal-content red-text">
                <h1>Suppression d'un projet</h1>
                <div class="divider mb-10"></div>
                <p>Êtes-vous sûr de vouloir supprimer ce projet?</p>
                <p>Toutes les informations liés au projet seront définitivement supprimées!</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="modal-close waves-effect waves-green btn-flat">Annuler</a>
                <a id="confirm-delete-user" href="<?= $router->getFullUrl('deleteProject', ['id' => $project->getId(), 'csrf' => $user->getCSRFToken()]) ?>" class="waves-effect waves-green btn-flat red white-text">Confirmer</a>
            </div>
        </div>
        <?php } ?>
        <div class="col s12 m8">
            <div class="card">
                <div class="card-content center-align">
                    <p class="card-title"><?= $project->getTitle(); ?></p>
                    <div class="divider"></div>
                    <p><?= $project->getDescription(); ?></p>
                    <p class="right-align"><span class="left"><?= $project->getCreatedAt(); ?></span><a href="<?= $router->getFullUrl('profile', ['id' => $project->getCreatedBy()->getId()]); ?>"><?= $project->getCreatedBy()->getUserName() . ' - ' . $project->getCreatedBy()->getFullName(); ?></a></p>
                    <div class="divider mb-10"></div>
                    <div class="row">
                        <?php foreach($rights as $key => $button) { ?>
                            <?php if($user->hasRight($key)) { ?>
                                <div class="col s12<?php if(count($rights) === 3) { echo ' m4'; } elseif(count($rights) === 2) { echo ' m6'; } ?>">
                                    <?= $button; ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>