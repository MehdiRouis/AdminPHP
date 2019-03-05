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

    /**
     * @Route(path="/projects/list" name="projects")
     * @throws \Exception
     */
    public function getList() {
        $this->user->restrict('view-projects');
        $projects = new Project();
        $projectList = $projects->getProjects('ORDER BY id DESC');
        $this->render('projects/list', ['pageName' => 'Liste des projets', 'projectList' => $projectList]);
    }

    /**
     * @Route(path="/project/:id/view" name="project")
     * @RouteParam(param=":id" regex="[\d]+")
     * @throws \Exception
     */
    public function getProject($id) {
        $this->user->restrict('view-projects');
        $project = new Project($id);
        if($project->getId()) {
            $rights = [];
            if($this->user->hasRight('validate-projects') && $project->getStatus()->getId() === 3) {
                $rights['validate-projects'] = '<a href="' . $this->getRouter()->getFullUrl('validateProject', ['id' => $project->getId(), 'csrf' => $this->user->getCSRFToken()]) . '" class="btn waves-effect waves-light green">Valider</a>';
            }
            if($this->user->hasRight('edit-projects')) {
                $rights['edit-projects'] = '<a href="" class="btn waves-effect waves-light grey">Modifier</a>';
            }
            if($this->user->hasRight('delete-projects')) {
                $rights['delete-projects'] = '<a href="#deleteModal" class="btn waves-effect waves-light red modal-trigger">Supprimer</a>';
            }
            $this->render('projects/profile', ['pageName' => $project->getTitle(), 'project' => $project, 'rights' => $rights, 'scripts' => ['js/projectProfile.js']]);
        } else {
            $this->security->safeLocalRedirect('default');
        }
    }

    /**
     * @Route(path="/project/validate/:id/:csrf" name="validateProject")
     * @RouteParam(param=":id" regex="[\d]+")
     * @RouteParam(param=":csrf" regex="[a-z0-9]+")
     * @throws \Exception
     */
    public function validateProject($id, $csrf) {
        $this->user->restrict('validate-projects');
        $this->user->matchCSRFToken($csrf);
        $project = new Project($id);
        if($project->getId()) {
            $project->validate();
            $this->security->safeLocalRedirect('dashboard|?action=success');
        }
        $this->security->safeLocalRedirect('default');
    }

    /**
     * @Route(path="/project/delete/:id/:csrf" name="deleteProject")
     * @RouteParam(param=":id" regex="[\d]+")
     * @RouteParam(param=":csrf" regex="[a-z0-9]+")
     * @throws \Exception
     */
    public function deleteProject($id, $csrf) {
        $this->user->restrict('delete-projects');
        $this->user->matchCSRFToken($csrf);
        $project = new Project($id);
        if($project->getId()) {
            $project->delete();
            $this->security->safeLocalRedirect('dashboard|?action=success');
        }
    }
}