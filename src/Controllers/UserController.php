<?php
/**
 * Created by PhpStorm.
 * User: stuuf
 * Date: 18/02/2019
 * Time: 10:12
 */

namespace Controllers;

use Models\Statistics\Stats;
use Models\Users\User;

/**
 * Class UserController
 * @package Controllers
 */
class UserController extends Controller {

    public function getDashboard() {
        $this->user->restrict('admin-access');
        $stats = new Stats();
        $this->render('account/dashboard', ['stats' => $stats]);
    }

    public function getAllUsers() {
        $this->user->restrict('view-users');
        $this->render('users/list', ['pageName' => 'Liste des utilisateurs', 'users' => $this->user->getUsers('ORDER BY id DESC'), 'scripts' => ['js/users.js']]);
    }

    public function getProfile($id) {
        echo $id;
    }

    public function deleteUser($id, $csrf) {
        $this->user->restrict('delete-users');
        $this->user->matchCSRFToken($csrf);
        $user = new User($id);
        if($user->getId()) {
            if($user->getProfileBanner(true)) {
                unlink(PROJECT_LIBS . '/../' . WEBSITE_ROOT_FOLDER_NAME . '/public/assets/img/profile/banners/' . $user->getProfileBanner(true));
            }
            $user->delete();
            $this->security->safeLocalRedirect('dashboard|?action=success');
        }
        $this->security->safeLocalRedirect('default');

    }

}