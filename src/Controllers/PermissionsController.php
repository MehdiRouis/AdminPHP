<?php
/**
 * Created by PhpStorm.
 * User: stuuf
 * Date: 18/02/2019
 * Time: 16:31
 */

namespace Controllers;

use App\Validators\Validator;
use Models\Globals\Post;
use Models\Users\Permission;
use Models\Users\Rank;

/**
 * Class PermissionsController
 * @package Controllers
 */
class PermissionsController extends Controller {

    public function getEdition() {
        $this->user->restrict('edit-permissions');
        $permissions = new Permission();
        $rank = new Rank();
        $this->render('permissions/list', ['pageName' => 'Liste des permissions', 'ranks' => $rank->getRanks('ORDER BY id DESC'), 'permissions' => $permissions->getPermissions('ORDER BY id'), 'scripts' => ['js/permissions.js']]);
    }

    public function postEdition() {
        $this->user->restrict('edit-permissions');
        $validator = new Validator();
        $validator->validate();
        $count = 0;
        foreach($_POST as $key => $value) {
            if(preg_match('/^minRank-[0-9]+$/', $key)) {
                $count++;
                $permissionId = (int) explode('-', $key)[1];
                $permission = new Permission($permissionId, 'id');
                if(!is_null($permission->getId())) {
                    if(intval($value)) {
                        $rank = new Rank($value);
                        if(is_null($rank->getId())) {
                            $validator->addError($key, 'Erreur interne... Merci de réessayer plus tard.');
                        }
                    } else {
                        $validator->addError($key, 'Erreur interne... Merci de réessayer plus tard.');
                    }
                } else {
                    $validator->addError($key, 'Erreur interne... Merci de réessayer plus tard.');
                }
            } else {
                if ($key !== 'CSRFToken') {
                    $validator->addError($key, 'Erreur interne... Merci de réessayer plus tard.');
                }
            }
        }
        if($count === 0) {
            $validator->addError('global', 'Erreur interne... Merci de réessayer plus tard.');
        }
        if(!$validator->isThereErrors()) {
            if (isset($permission, $rank)) {
                $permission->setMinRank($rank->getId());
                $this->security->safeExternalRedirect($this->getRouter()->getFullUrl('permissions') . '?success=true');
            }
        }
        $permissions = new Permission();
        $rank = new Rank();
        $this->render('permissions/list', ['pageName' => 'Liste des permissions', 'ranks' => $rank->getRanks('ORDER BY id DESC'), 'permissions' => $permissions->getPermissions('ORDER BY id'), 'scripts' => ['js/permissions.js'], 'errors' => $validator->getErrors()]);
    }
}