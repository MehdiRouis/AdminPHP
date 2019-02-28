<?php
/**
 * Created by PhpStorm.
 * User: stuuf
 * Date: 18/02/2019
 * Time: 15:55
 */

namespace Models\Statistics;

use Models\Database\PDOConnect;

/**
 * Class Stats
 * @package Models\Statistics
 */
class Stats {

    private $db;

    public function __construct() {
        $this->db = new PDOConnect();
    }

    /**
     * @return int
     */
    public function countCreatedProjects() {
        $req = $this->db->query('SELECT id FROM alive_projects');
        return $req->rowCount();
    }

    public function countFinishedProjects() {
        $req = $this->db->query('SELECT id FROM alive_projects WHERE statusId = ?', [4]);
        return $req->rowCount();
    }

    public function countUsers() {
        $req = $this->db->query('SELECT id FROM alive_users');
        return $req->rowCount();
    }
}