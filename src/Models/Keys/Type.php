<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 07/02/19
 * Time: 08:20
 */

namespace Models\Keys;


use Models\Database\PDOConnect;

/**
 * Class Type
 * @package Models\Keys
 */
class Type {

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
    private $type;

    /**
     * Type constructor.
     * @param $id
     */
    public function __construct($id) {
        $this->db = new PDOConnect();
        $req = $this->db->query('SELECT * FROM alive_keys_types WHERE id = ?', [$id]);
        if($req->rowCount() > 0) {
            $type = $req->fetch();
            $this->id = $type->id;
            $this->type = $type->type;
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
        return $this->type;
    }
}