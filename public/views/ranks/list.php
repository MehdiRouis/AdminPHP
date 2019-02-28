<?php
/**
 * Created by PhpStorm.
 * User: stuuf
 * Date: 18/02/2019
 * Time: 16:40
 */
/**
 * @var array $ranks
 * @var \Models\Users\User $user
 */
?>
<main class="container-fluid">
    <div class="card">
        <div class="card-content">
            <p class="card-title">Liste des rangs</p>
            <div class="divider mb-10"></div>
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Icône</th>
                    <?php if($user->hasRight('edit-ranks')) { ?>
                        <th>Modifier</th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach($ranks as $rank) { /** @var \Models\Users\Rank $rank */?>
                    <tr>
                        <td data-label="#"><?= $rank->getId(); ?></td>
                        <td data-label="Nom"><?= $rank->getName(); ?></td>
                        <td data-label="Icône"><?= $rank->getIcon(false); ?></td>
                        <td data-label="Modifier">
                            <?php if($user->hasRight('edit-ranks')) { ?>
                                <a href="<?= $router->getFullUrl('editRank', ['id' => $rank->getId()]); ?>" class="btn grey waves-effect waves-light">Modifier</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
