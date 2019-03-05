<?php
/**
 * Created by PhpStorm.
 * User: stuuf
 * Date: 18/02/2019
 * Time: 10:12
 */

namespace Controllers;

use App\Validators\Validator;
use Models\Globals\Post;
use Models\Statistics\Stats;
use Models\Users\Rank;
use Models\Users\User;

/**
 * Class UserController
 * @package Controllers
 */
class UserController extends Controller {

    /**
     * @Route(path="/account/dashboard" name="dashboard")
     * @throws \Exception
     */
    public function getDashboard() {
        $this->user->restrict('admin-access');
        $stats = new Stats();
        $this->render('account/dashboard', ['stats' => $stats]);
    }

    /**
     * @Route(path="/users/all" name="allUsers")
     * @throws \Exception
     */
    public function getAllUsers() {
        $this->user->restrict('view-users');
        $this->render('users/list', ['pageName' => 'Liste des utilisateurs', 'users' => $this->user->getUsers('ORDER BY id DESC'), 'scripts' => ['js/users.js']]);
    }

    /**
     * @Route(path="/user/profile/:id" name="userProfile")
     * @RouteParam(param=":id" regex="[\d]+")
     * @throws \Exception
     */
    public function getProfile($id) {
        $redirect = true;
        $user = new User($id);
        if($user->getId()) {
            $redirect = false;
            $this->render('users/profile', ['pageName' => $user->getUserName(), 'userProfile' => $user]);
        }
        if($redirect) {
            $this->security->safeLocalRedirect('default');
        }
    }

    /**
     * @Route(path="/users/edit/global/:id" name="editUser")
     * @RouteParam(param=":id" regex="[\d]+")
     * @throws \Exception
     */
    public function getEditUser($id) {
        $this->user->restrict('edit-users');
        $userEdit = new User($id);
        $redirect = true;
        if ($userEdit->getId()) {
            $redirect = false;
            $this->render('users/edit', ['pageName' => 'Éditer un utilisateur', 'userEdit' => $userEdit, 'ranks' => $this->user->getLowerRanks(), 'scripts' => ['js/editUser.js']]);
        }
        if($redirect) {
            $this->security->safeLocalRedirect('default');
        }
    }

    /**
     * @Route(path="/users/edit/password/:id" name="editUserPassword")
     * @RouteParam(param=":id" regex="[\d]+")
     * @throws \Exception
     */
    public function getEditUserPassword($id) {
        $this->user->restrict('edit-users');
        $userEdit = new User($id);
        $redirect = true;
        if ($userEdit->getId()) {
            $redirect = false;
            $this->render('users/edit-password', ['pageName' => 'Éditer un mot de passe', 'userEdit' => $userEdit, 'ranks' => $this->user->getLowerRanks(), 'scripts' => ['js/editUser.js']]);
        }
        if($redirect) {
            $this->security->safeLocalRedirect('default');
        }
    }

    /**
     * @Route(path="/user/delete/:id/:csrf" name="deleteUser")
     * @RouteParam(param=":id" regex="[\d]+")
     * @RouteParam(param=":csrf" regex="[a-z0-9]+")
     * @throws \Exception
     */
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

    /**
     * @Route(path="/user/edit/gobal" name="pEditUser")
     * @throws \Exception
     */
    public function postEditUser() {
        $redirect = true;
        $post = new Post();
        $userNameK = 'userName';
        $lastNameK = 'lastName';
        $firstNameK = 'firstName';
        $rankIdK = 'rankId';
        $emailK = 'email';
        $phoneNumberK = 'phoneNumber';
        $birthDayK = 'birthDay';
        $shopPointK = 'shopPoint';
        $profileTypeK = 'profileType';
        $validator = new Validator([
            'username' => [$userNameK],
            'name' => [$lastNameK, $firstNameK],
            'rankId' => [$rankIdK],
            'email' => [$emailK],
            'phoneNumber' => [$phoneNumberK],
            'adultBirthDay' => [$birthDayK],
            'integer' => [$shopPointK],
            'profileType' => [$profileTypeK]
        ]);
        $validator->validate();
        $userEdit = new User($post->getValue('userId'));
        $checkUserName = new User($post->getValue($userNameK), 'userName');
        if($checkUserName->getId()) {
            if($checkUserName->getId() !== $userEdit->getId()) {
                $validator->addError($userNameK, 'Nom d\'utilisateur déjà pris.');
            }
        }
        $checkEmail = new User($post->getValue($emailK), 'email');
        if($checkEmail->getId()) {
            if($checkEmail->getId() !== $userEdit->getId()) {
                $validator->addError($emailK, 'Adresse email déjà prise.');
            }
        }
        $rank = new Rank($post->getValue('rankId'));
        if($rank->getId()) {
            if($rank->getId() > $userEdit->getId()) {
                $validator->addError($rankIdK, 'Vous n\'avez pas les droits.');
            }
        }
        if ($userEdit->getId() && $rank->getId()) {
            if(!$validator->isThereErrors()) {
                $userName = $post->getValue($userNameK);
                $lastName = $post->getValue($lastNameK);
                $firstName = $post->getValue($firstNameK);
                $rankId = $post->getValue($rankIdK);
                $email = $post->getValue($emailK);
                $phoneNumber = $post->getValue($phoneNumberK);
                $birthDay = $post->getValue($birthDayK);
                $shopPoint = $post->getValue($shopPointK);
                $profileType = $post->getValue($profileTypeK);
                $userEdit->editAll($this->security->secureValue($userName), $this->security->secureValue($lastName), $this->security->secureValue($firstName), $rankId, $email, $phoneNumber, $birthDay, $shopPoint, $profileType);
                $this->security->safeLocalRedirect('editUser|?action=success', ['id' => $userEdit->getId()]);
            }
            $redirect = false;
            $this->render('users/edit', ['pageName' => 'Éditer un utilisateur', 'userEdit' => $userEdit, 'errors' => $validator->getErrors(), 'ranks' => $this->user->getLowerRanks(), 'scripts' => ['js/editUser.js']]);
        }
        if($redirect) {
            $this->security->safeLocalRedirect('default');
        }
    }

    /**
     * @Route(path="/user/edit/password" name="pEditUserPassword")
     * @throws \Exception
     */
    public function postEditUserPassword() {
        $newPasswordK = 'newPassword';
        $reNewPasswordK = 'reNewPassword';
        $userIdK = 'userId';
        $post = new Post();
        $validator = new Validator([
            'password' => [$newPasswordK]
        ]);
        $validator->validate();
        $userEdit = new User($post->getValue($userIdK));
        if(!$userEdit->getId()) {
            $this->security->safeLocalRedirect('default');
        }

        if($post->getValue($newPasswordK) !== $post->getValue($reNewPasswordK)) {
            $validator->addError($reNewPasswordK, 'Les mots de passes ne correspondent pas.');
        }
        if(!$validator->isThereErrors()) {
            $password = $this->security->secureValue($post->getValue($newPasswordK));
            $userEdit->setPassword($password);
            $this->sendMail('AliveWebProject - Modification de votre compte', [$userEdit->getEmail()], '<p>Le mot de passe de votre compte a été modifié!</p><p>Le mot de passe est maintenant le suivant : ' . $post->getValue($newPasswordK) . '</p>');
            $this->security->safeLocalRedirect('editUserPassword|?action=success', ['id' => $userEdit->getId()]);
        }
        $this->render('users/edit-password', ['pageName' => 'Éditer un mot de passe', 'userEdit' => $userEdit, 'errors' => $validator->getErrors()]);
    }

}