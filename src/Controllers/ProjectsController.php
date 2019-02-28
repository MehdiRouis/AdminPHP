<?php
/**
 * Created by PhpStorm.
 * User: stuuf
 * Date: 25/02/2019
 * Time: 19:12
 */

namespace Controllers;


use Models\Projects\Project;

class ProjectsController extends Controller {

    public function getList() {
        $this->user->restrict('view-projects');
        $projects = new Project();
        $projectList = $projects->getProjects('ORDER BY id DESC');
        $this->render('projects/list', ['pageName' => 'Liste des projets', 'projectList' => $projectList]);
    }

    public function getProject($id) {
        $this->user->restrict('view-projects');
        $project = new Project($id);
        if($project->getId()) {
            $rights = [];
            if($this->user->hasRight('validate-projects')) {
                $rights['validate-projects'] = '<button type="submit" class="btn waves-effect waves-light green">Valider le projet</button>';
            }
            if($this->user->hasRight('edit-projects')) {
                $rights['edit-projects'] = '<a href="" class="btn waves-effect waves-light grey">Modifier</a>';
            }
            if($this->user->hasRight('delete-projects')) {
                $rights['delete-projects'] = '<a href="" class="btn waves-effect waves-light red">Supprimer</a>';
            }
            $this->render('projects/profile', ['pageName' => $project->getTitle(), 'project' => $project, 'rights' => $rights]);
        } else {
            $this->security->safeLocalRedirect('default');
        }
    }
}