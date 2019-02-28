<?php
/**
 * @var \Models\Users\User $user
 * @var \Models\Statistics\Stats $stats
 */
?>
<main class="container-fluid">
    <div class="row white-text">
        <div class="col s12 m3">
            <div class="card gradient-45deg-amber-amber">
                <div class="card-content">
                    <p class="card-title">Vous êtes</p>
                    <div class="divider mb-20"></div>
                    <p class="right-align mt-10"><?= $user->getRank()->getName(); ?></p>
                </div>
            </div>
        </div>
        <div class="col s12 m3">
            <div class="card gradient-45deg-green-teal">
                <div class="card-content">
                    <p class="card-title">Projets crées</p>
                    <div class="divider mb-10"></div>
                    <p class="right-align"><?= $stats->countCreatedProjects(); ?></p>
                </div>
            </div>
        </div>
        <div class="col s12 m3">
            <div class="card gradient-45deg-indigo-light-blue">
                <div class="card-content">
                    <p class="card-title">Projets terminés</p>
                    <div class="divider mb-10"></div>
                    <p class="right-align"><?= $stats->countFinishedProjects(); ?></p>
                </div>
            </div>
        </div>
        <div class="col s12 m3">
            <div class="card gradient-45deg-purple-deep-orange">
                <div class="card-content">
                    <p class="card-title">Nombre d'utilisateurs</p>
                    <div class="divider mb-10"></div>
                    <p class="right-align"><?= $stats->countUsers(); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php if(isset($_GET['action'])) { ?>
        <div class="card gradient-45deg-green-teal">
            <div class="card-content center-align white-text">
                <p class="card-title">Succès!</p>
                <div class="divider"></div>
                <p>L'action a été réalisée avec succès!</p>
            </div>
        </div>
    <?php } ?>
    <?php if($user->hasRight('view-users')) { ?>
    <div class="row">
        <div class="col s12 xl8">
            <div class="card">
                <div class="card-content black-text center-align">
                    <p class="card-title">10 derniers inscrits</p>
                    <div class="divider"></div>
                    <table>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Pseudo</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Date de création</th>
                            <?php if($user->hasRight('view-users')) { ?>
                            <th>Voir</th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($user->getUsers('ORDER BY id DESC', 10) as $tenLastUsers) { /** @var \Models\Users\User $tenLastUsers */?>
                            <tr>
                                <td data-label="#"><?= $tenLastUsers->getId(); ?></td>
                                <td data-label="Pseudo"><?= $tenLastUsers->getUserName(); ?></td>
                                <td data-label="Nom"><?= $tenLastUsers->getLastName(); ?></td>
                                <td data-label="Prénom"><?= $tenLastUsers->getFirstName(); ?></td>
                                <td data-label="Date de création"><?= $tenLastUsers->getCreatedAt(); ?></td>
                                <?php if($user->hasRight('view-users')) { ?>
                                <td data-label="Voir">
                                    <a href="<?= $router->getFullUrl('userProfile', ['id' => $tenLastUsers->getId()]); ?>" class="btn grey waves-effect waves-light">Voir</a>
                                </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="divider mb-10"></div>
                    <a href="<?= $router->getFullUrl('allUsers'); ?>" class="btn pink waves-effect waves-light">Voir plus</a>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</main>