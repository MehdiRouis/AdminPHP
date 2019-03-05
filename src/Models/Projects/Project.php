<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 28/01/19
 * Time: 15:23
 */

namespace Models\Projects;


use App\Protections\Security;
use App\Validators\Validator;
use Models\Database\PDOConnect;
use App\Date\Parser;
use Models\Globals\Post;
use Models\Users\User;

class Project {

    /**
     * @var PDOConnect
     */
    private $db;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Status
     */
    private $status;

    /**
     * @var User
     */
    private $createdBy;

    /**
     * @var int
     */
    private $createdAt;

    /**
     * @var int
     */
    private $editedAt;

    /**
     * Project constructor.
     * @param bool|int $projectId
     */
    public function __construct($projectId = false) {
        $this->db = new PDOConnect();
        if($projectId) {
            $req = $this->db->query('SELECT * FROM alive_projects WHERE id = ?', [$projectId]);
            if($req->rowCount() > 0) {
                $project = $req->fetch();
                $this->id = $project->id;
                $this->name = $project->name;
                $this->description = $project->description;
                $this->status = new Status($project->statusId);
                $this->createdBy = new User($project->createdBy);
                $this->createdAt = $project->createdAt;
                $this->editedAt = $project->editedAt;
            }
        }
    }

    /**
     * @param string $title
     * @param string $description
     * @param int $userId
     * @return array|bool
     */
    public function add($title, $description, $userId): array {
        $validator = new Validator([
            'title' => [$title],
            'description' => [$description]
        ], 'alive_projects');
        $validator->validate();
        $req = $this->db->query('SELECT id FROM alive_projects WHERE createdBy = ? AND statusId = ?', [$userId, 3]);
        if($req->rowCount() > 0) {
            $validator->addError('global', 'Vous avez déjà un projet en attente de modération.');
        }
        $security = new Security();
        $post = new Post();
        if(!$validator->isThereErrors()) {
            $req = $this->db->query('INSERT INTO alive_projects (name, description, statusId, createdBy, createdAt) VALUES (?,?,?,?,?)', [$security->secureValue($post->getValue($title)), $security->secureValue($post->getValue($description)), 3, $userId, time()]);
            if($req) {
                $projects = $this->db->query('SELECT id FROM alive_projects WHERE createdBy = ? ORDER BY id DESC', [$userId]);
                if($projects->rowCount() > 0) {
                    $project = $projects->fetch();
                    $req = $this->db->query('INSERT INTO alive_projects_members (userId, `rank`, projectId, joinedAt) VALUES (?, ?, ?, ?)', [$userId, 3, $project->id, time()]);
                    if($req) {
                        return [];
                    }
                }
            } else {
                $validator->addError($title, 'Erreur lors de la requête... Merci de réessayer plus tard.');
            }
        }
        return $validator->getErrors();
    }

    /**
     * @param bool|string $order
     * @param bool|string $limit
     * @return array
     */
    public function getProjects($order = false, $limit = false) {
        $order = $order ? ' ' . $order : '';
        $limit = $limit ? ' LIMIT ' . $limit : '';
        $req = $this->db->query('SELECT id FROM alive_projects' . $order . $limit);
        $projects = [];
        while($project = $req->fetch()) {
            $projects[] = new Project($project->id);
        }
        return $projects;
    }

    /**
     * @return int
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return (string) $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @return Status
     */
    public function getStatus(): ?Status {
        return $this->status;
    }

    /**
     * @param int $statusId
     * @return \PDOStatement
     */
    public function setStatus($statusId): \PDOStatement {
        $req = $this->db->query('UPDATE alive_projects SET statusId = ?, editedAt = ? WHERE id = ?', [$statusId, time(), $this->getId()]);
        return $req;
    }

    /**
     * @return User
     */
    public function getCreatedBy(): User {
        return $this->createdBy;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string {
        $date = new Parser($this->createdAt);
        return $date->format();
    }

    /**
     * @return string
     */
    public function getEditedAt(): string {
        $date = new Parser($this->editedAt);
        return $date->format();
    }

    /**
     * @return \PDOStatement
     */
    public function validate(): \PDOStatement {
        return $this->setStatus(2);
    }

    public function delete(): \PDOStatement {
        $req = $this->db->query('DELETE FROM alive_projects WHERE id = ?', [$this->getId()]);
        return $req;
    }

}