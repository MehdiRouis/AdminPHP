<?php
/**
 * Created by PhpStorm.
 * User: stuuf
 * Date: 19/02/2019
 * Time: 08:26
 */

namespace Controllers;


use App\Validators\Validator;
use Models\Globals\Post;
use Models\Users\Rank;

class RanksController extends Controller {

    public function getList() {
        $this->user->restrict('view-ranks');
        $rank = new Rank();
        $this->render('ranks/list', ['pageName' => 'Rangs', 'ranks' => $rank->getRanks('ORDER BY id DESC')]);
    }

    public function getEdition($id) {
        $this->user->restrict('edit-ranks');
        $rank = new Rank($id);
        if(!is_null($rank->getId())) {
            $this->render('ranks/edit', ['pageName' => 'Éditer un rang', 'rank' => $rank, 'scripts' => ['js/editRank.js']]);
        } else {
            $this->security->safeLocalRedirect('default');
        }
    }
    public function postEdition() {
        $this->user->restrict('edit-ranks');
        $post = new Post();
        $name = 'rankName';
        $icon = 'rankIcon';
        $color = 'rankColor';
        $id = $post->getValue('rankId');
        $validator = new Validator([
            'rankName' => [$name],
            'icon' => [$icon],
            'color' => [$color]
        ]);
        $validator->validate();
        $rank = new Rank($id);
        if(!$validator->isThereErrors()) {
            $rankName = $post->getValue($name);
            $rankIcon = $post->getValue($icon);
            $rankColor = $post->getValue($color);
            $rank->updateAll($rankName, $rankIcon, $rankColor);
            $this->security->safeLocalRedirect('ranks');
        }
        $this->render('ranks/edit', ['pageName' => 'Éditer un rang', 'rank' => $rank, 'scripts' => ['js/editRank.js'], 'errors' => $validator->getErrors()]);
    }
}