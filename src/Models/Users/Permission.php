<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 14:46
 */

namespace Models\Users;

use Models\Database\PDOConnect;

class Permission {

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
    private $parseName;

    /**
     * @var string
     */
    private $parseDescription;

    /**
     * @var int
     */
    private $minRank;

    /**
     * Permission constructor.
     * @param bool $value
     * @param string $searchType
     */
    public function __construct($value = false, $searchType = 'name') {
        $this->db = new PDOConnect();
        if($value) {
            $req = $this->db->query("SELECT * FROM alive_users_permissions WHERE {$searchType} = ?", [$value]);
            if($req->rowCount() > 0) {
                $permission = $req->fetch();
                $this->id = $permission->id;
                $this->name = $permission->name;
                $this->parseName = $permission->parseName;
                $this->parseDescription = $permission->parseDescription;
                $this->minRank = $permission->minRank;
            } else {
                $this->name = 'undefined';
                $this->parseName = 'Permission introuvable.';
                $this->parseDescription = 'Permission introuvable.';
                $this->minRank = 255;
            }
        }
    }

    /**
     * @return array
     */
    public function getPermissions($order = false, $limit = false) {
        $order = $order ? ' ' . $order : '';
        $limit = $limit ? ' LIMIT ' . $limit : '';
        $req = $this->db->query('SELECT id FROM alive_users_permissions' . $order . $limit);
        $permissions = [];
        while($permission = $req->fetch()) {
            $permissions[] = new Permission($permission->id, 'id');
        }
        return $permissions;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getParseName(): ?string
    {
        return $this->parseName;
    }

    /**
     * @return string
     */
    public function getParseDescription(): ?string
    {
        return $this->parseDescription;
    }

    /**
     * @return int
     */
    public function getMinRank(): int
    {
        return $this->minRank;
    }

    /**
     * @param int $minRank
     * @return \PDOStatement
     */
    public function setMinRank($minRank): \PDOStatement {
        $req = $this->db->query('UPDATE alive_users_permissions SET minRank = ? WHERE id = ?', [$minRank, $this->getId()]);
        return $req;
    }

    /**
     * @param int $rankId
     * @return bool|null
     */
    public function hasRight($rankId): ?bool {
        if($this->getMinRank() <= $rankId) {
            return true;
        }
        return false;
    }

}