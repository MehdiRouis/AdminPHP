<?php
/**
 * @var \Models\Users\User $user
 * @var array $users
 */
?>
<main class="container-fluid">
    <div class="card">
        <div class="card-content">
            <p class="card-title">Liste des utilisateurs</p>
            <div class="divider mb-10"></div>
            <?php if($user->hasRight('edit-users')) { ?>
                <div id="deleteModal" class="modal">
                    <div class="modal-content red-text">
                        <h1>Suppression d'un projet</h1>
                        <div class="divider mb-10"></div>
                        <p>Êtes-vous sûr de vouloir supprimer cet utilisateur?</p>
                        <p>Toutes ses informations seront définitivement supprimées!</p>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="modal-close waves-effect waves-green btn-flat">Annuler</a>
                        <a id="confirm-delete-user" href="" class="waves-effect waves-green btn-flat red white-text">Confirmer</a>
                    </div>
                </div>
            <?php } ?>
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Pseudo</th>
                    <th>NOM Prénom</th>
                    <th>Date de naissance</th>
                    <?php if($user->hasRight('edit-users')) { ?>
                        <th>Modifier</th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach($users as $userList) { /** @var \Models\Users\User $userList */?>
                    <tr>
                        <td data-label="#"><?= $userList->getId(); ?></td>
                        <td data-label="Nom"><?= $userList->getUserName(); ?></td>
                        <td data-label="Nom"><?= $userList->getFullName(); ?></td>
                        <td data-label="Nom"><?= $userList->getBirthDay(); ?></td>
                        <td data-label="Modifier">
                            <?php if($user->hasRight('edit-users')) { ?>
                                <a href="<?= $router->getFullUrl('editUser', ['id' => $userList->getId()]); ?>" class="btn grey waves-effect waves-light">Modifier</a>
                            <?php } ?>
                        </td>
                        <td data-label="Supprimer">
                            <?php if($user->hasRight('delete-users')) { ?>
                                <a href="#deleteModal" data-link="<?= $router->getFullUrl('deleteUser', ['id' => $userList->getId(), 'csrf' => $user->getCSRFToken()]); ?>" class="btn red waves-effect waves-light modal-trigger delete-user">Supprimer</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>