<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 07/02/19
 * Time: 08:24
 */

namespace Models\Keys;

use Models\Database\PDOConnect;

/**
 * Class Status
 * @package Models\Keys
 */
class Status {

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
     * Status constructor.
     * @param int $id
     */
    public function __construct($id) {
        $this->db = new PDOConnect();
        $req = $this->db->query('SELECT * FROM alive_keys_status WHERE id = ?', [$id]);
        if($req->rowCount() > 0) {
            $status = $req->fetch();
            $this->id = $status->id;
            $this->name = $status->name;
        }
    }

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string {
        return $this->name;
    }

}