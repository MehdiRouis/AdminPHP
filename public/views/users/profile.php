<?php
/**
 * @var \Models\Users\User $userProfile
 * @var \Models\Users\User $user
 * @var array $errors
 */
?>
<main class="container-fluid">
    <div id="profile-page-header" class="card">
        <div class="card-image waves-effect waves-block waves-light">
            <img class="activator" src="<?= $userProfile->getProfileBanner(); ?>" alt="user background">
        </div>
        <div class="card-content card-avatar center-align">
            <div class="row">
                <div class="circle white darken-1 black-text hoverable avatar">
                    <p><?= $userProfile->getInitialUserName(); ?></p>
                </div>
                <div class="col s12">
                    <h4 class="card-title grey-text text-darken-4"><?= $userProfile->getFullName(); ?></h4>
                    <p class="medium-small grey-text">@<?= $userProfile->getUserName(); ?></p>
                </div>
            </div>
        </div>
    </div>
</main>
