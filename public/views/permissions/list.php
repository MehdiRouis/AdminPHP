<?php
/**
 * Created by PhpStorm.
 * User: stuuf
 * Date: 18/02/2019
 * Time: 16:40
 */
/**
 * @var array $ranks
 * @var array $permissions
 * @var array $errors
 * @var \Models\Users\User $user
 */
?>
<main class="container-fluid">
    <div class="card">
        <div class="card-content">
            <p class="card-title">Liste des permissions</p>
            <div class="divider mb-10"></div>
            <?php if(isset($errors['global'])) { ?>
                <span class="helper-text red-text"><?= $errors['global']; ?></span>
            <?php } ?>
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Rang minimum</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($permissions as $permission) { /** @var \Models\Users\Permission $permission */?>
                    <tr>
                        <td data-label="#"><?= $permission->getId(); ?></td>
                        <td data-label="Nom"><?= $permission->getParseName(); ?></td>
                        <td data-label="Description"><?= $permission->getParseDescription(); ?></td>
                        <td data-label="Rang minimum">
                            <form method="POST" action="<?= $router->getFullUrl('pEditPermission'); ?>" class="row">
                                <div class="input-field col s12 m8">
                                    <select id="minRank-<?= $permission->getId(); ?>" name="minRank-<?= $permission->getId(); ?>">
                                        <?php foreach($ranks as $rank) { /** @var \Models\Users\Rank $rank */ ?>
                                            <option value="<?= $rank->getId(); ?>"<?= $permission->getMinRank() === $rank->getId() ? ' selected' : ''; ?>><?= $rank->getName(); ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="minRank-<?= $permission->getId(); ?>">Rang minimum</label>
                                    <?php if(isset($errors['minRank-' . $permission->getId()])) { ?>
                                        <span class="helper-text red-text"><?= $errors['minRank-' . $permission->getId()]; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="input-field col s12 m4">
                                    <input type="hidden" name="CSRFToken" value="<?= $user->getCSRFToken(); ?>" />
                                    <button type="submit" class="btn grey waves-effect waves-light">Modifier</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
