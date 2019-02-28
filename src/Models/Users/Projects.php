<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 28/01/19
 * Time: 15:19
 */

namespace Models\Users;


use Models\Database\PDOConnect;
use Models\Projects\Project;

class Projects {

    /**
     * @var PDOConnect
     */
    private $db;

    /**
     * @var array
     */
    private $projects = [];

    /**
     * @var int
     */
    private $userId;

    /**
     * Projects constructor.
     * @param bool|int $userId
     */
    public function __construct($userId = false) {
        $this->db = new PDOConnect();
        if($userId) {
            $this->userId = $userId;
            $req = $this->db->query('SELECT * FROM alive_projects_members WHERE userId = ?', [$userId]);
            if($req->rowCount() > 0) {
                while($project = $req->fetch()) {
                    $this->projects[] = new Project($project->projectId);
                }
            }
        }
    }

    /**
     * @return int
     */
    public function countCreatedProjects(): int {
        return count($this->getAllCreatedProjects());
    }

    /**
     * @return int
     */
    public function countFinishedProjects(): int {
        $finishedProjects = 0;
        $projects = $this->getAllProjects();
        foreach($projects as $project) {
            /** @var Project $project */
            if($project->getStatus()->getId() === 5) {
                $finishedProjects++;
            }
        }
        return $finishedProjects;
    }

    /**
     * @return int
     */
    public function countAllProjects(): int {
        return count($this->projects);
    }

    /**
     * @return array
     */
    public function getAllProjects(): array {
        return $this->projects;
    }

    /**
     * @return array
     */
    public function getAllCreatedProjects(): array {
        $projects = [];
        if($this->userId !== null) {
            $req = $this->db->query('SELECT id FROM alive_projects WHERE createdBy = ?', [$this->userId]);
            while($project = $req->fetch()) {
                $projects[] = new Project($project->id);
            }
        }
        return $projects;
    }
}