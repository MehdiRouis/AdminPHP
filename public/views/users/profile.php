<?php
/**
 * @var \Models\Users\User $profileUser
 */
?>
<main class="container-fluid">
    <div class="card">
        <div class="card-content">
            <p class="card-title">Informations de <?= $profileUser->getUserName(); ?> - <?= $profileUser->getFullName(); ?></p>
        </div>
    </div>
</main>