<?php
/**
 * Created by PhpStorm.
 * User: stuuf
 * Date: 25/02/2019
 * Time: 19:18
 */
/**
 * @var array $projectList
 * @var \Models\Users\User $user
 */
?>
<main class="container-fluid">
    <div class="card">
        <div class="card-content">
            <p class="card-title">Liste des projets</p>
            <div class="divider mb-10"></div>
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Crée par</th>
                    <th>Statut</th>
                    <th>Crée le</th>
                    <th>Accès</th>
                    <?php if($user->hasRight('edit-projects')) { ?>
                        <th>Modifier</th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach($projectList as $project) { /** @var \Models\Projects\Project $project */?>
                    <tr>
                        <td data-label="#"><?= $project->getId(); ?></td>
                        <td data-label="Nom"><?= $project->getTitle(); ?></td>
                        <td data-label="Crée par"><?= $project->getCreatedBy()->getUserName(); ?></td>
                        <td data-label="Statut"><?= $project->getStatus()->getName(); ?></td>
                        <td data-label="Crée le"><?= $project->getCreatedAt(); ?></td>
                        <td data-label="Accès"><a target="_blank" href="<?= $router->getFullUrl('project', ['id' => $project->getId()]); ?>" class="btn waves-effect waves-light">Voir</a></td>
                        <td data-label="Modifier">
                            <?php if($user->hasRight('edit-projects')) { ?>
                                <a target="_blank" href="<?= $router->getFullUrl('editRank', ['id' => $project->getId()]); ?>" class="btn grey waves-effect waves-light">Modifier</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
