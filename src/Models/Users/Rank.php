<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 14:18
 */

namespace Models\Users;


use Models\Database\PDOConnect;

class Rank {

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
    private $icon;

    /**
     * @var string
     */
    private $color;

    /**
     * Rank constructor.
     * @param bool|int $id
     */
    public function __construct($id = false) {
        $this->db = new PDOConnect();
        if ($id) {
            $req = $this->db->query('SELECT * FROM alive_users_ranks WHERE id = ?', [$id]);
            if ($req->rowCount() > 0) {
                $rank = $req->fetch();
                $this->id = $rank->id;
                $this->name = $rank->name;
                $this->icon = $rank->icon;
                $this->color = $rank->color;
            }
        }
    }

    /**
     * @param bool $order
     * @param bool $limit
     * @return array
     */
    public function getRanks($order = false, $limit = false) {
        $order = $order ? ' ' . $order : '';
        $limit = $limit ? ' LIMIT ' . $limit : '';
        $req = $this->db->query('SELECT id FROM alive_users_ranks' . $order . $limit);
        $ranks = [];
        while($rank = $req->fetch()) {
            $ranks[] = new Rank($rank->id);
        }
        return $ranks;
    }

    public function getRankedUsers($order = 'ORDER BY id DESC', $limit = false) {
        $permission = new Permission('admin-access');
        $limit = $limit ? ' LIMIT ' . $limit : '';
        $req = $this->db->query('SELECT id FROM alive_users WHERE `rank` >= ? ' . $order . $limit, [$permission->getMinRank()]);
        $users = [];
        if($req->rowCount() > 0) {
            while($user = $req->fetch()) {
                $users[] = new User($user->id);
            }
        }
        return $users;
    }

    public function updateAll($name, $icon, $color) {
        $req = $this->db->query('UPDATE alive_users_ranks SET name = ?, icon = ?, color = ? WHERE id = ?', [$name, $icon, $color, $this->getId()]);
        return $req;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param bool $withHtmlIcon
     * @return string
     */
    public function getName($withHtmlIcon = true): ?string
    {
        $icon = !is_null($this->getIcon()) ? $this->getIcon() . ' ' : '#fff';
        return $withHtmlIcon ? '<span style="color : ' . $this->color . ';padding : 10px; background-color : rgba(0,0,0,0.7);">'. $icon . $this->name . '</span>' : $this->name;
    }

    /**
     * @param bool $html
     * @return string|null
     */
    public function getIcon($html = true): ?string {
        $icon = !is_null($this->icon) ? $html ? '<i class="' . $this->icon . '"></i>' : $this->icon : '';
        return $icon;
    }

    /**
     * @return string
     */
    public function getColor(): ?string {
        return $this->color;
    }

}