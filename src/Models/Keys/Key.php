<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 07/02/19
 * Time: 08:17
 */

namespace Models\Keys;

use App\Date\Parser;
use App\Protections\Security;
use Models\Database\PDOConnect;
use Models\Users\User;

/**
 * Class Key
 * @package Models\Keys
 */
class Key {

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
    private $code;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $value;

    /**
     * @var Status
     */
    private $status;

    /**
     * @var int
     */
    private $createdAt;

    /**
     * Key constructor.
     * @param bool|string $key
     */
    public function __construct($key = false) {
        $this->db = new PDOConnect();
        if($key) {
            $req = $this->db->query('SELECT * FROM alive_keys WHERE id = ?', [$key]);
            if($req->rowCount() > 0) {
                $keyfetch = $req->fetch();
                $this->id = $keyfetch->id;
                $this->code = $keyfetch->code;
                $this->type = new Type($keyfetch->type);
                $this->user = new User($keyfetch->userId);
                $this->value = $keyfetch->value;
                $this->status = new Status($keyfetch->status);
                $this->createdAt = $keyfetch->createdAt;
            }
        }
    }

    public function generate($type, $userId, $status, $value = null, $code = false) {
        $security = new Security();
        $code = $code ? $code : $security->generateRandomKey();
        $req = $this->db->query('SELECT id FROM alive_keys WHERE userId = ? AND type = ?', [$userId, $type]);
        if($req->rowCount() === 0) {
            $this->db->query('INSERT INTO alive_keys (code, type, userId, value, status, createdAt) VALUES (?,?,?,?,?,?)', [$code, $type, $userId, $value, $status, time()]);
        } else {
            $verif = $req->fetch();
            $key = new Key($verif->id);
            if((int) $key->getCreatedAt('time') < strtotime('-15min')) {
                $this->db->query('UPDATE alive_keys SET code = ?, value = ?, status = ?, createdAt = ? WHERE userId = ? AND type = ?', [$code, $value, $status, time(), $userId, $type]);
            } else {
                return false;
            }
        }
        return $code;
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
    public function getKey(): ?string {
        return $this->code;
    }

    /**
     * @return Type|null
     */
    public function getType(): ?Type {
        return $this->type;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string {
        return $this->value;
    }

    /**
     * @return Status|null
     */
    public function getStatus(): ?Status {
        return $this->status;
    }

    /**
     * @param int $value
     * @return \PDOStatement
     */
    public function setStatus($value) {
        $req = $this->db->query('UPDATE alive_keys SET status = ? WHERE id = ?', [$value, $this->getId()]);
        return $req;
    }

    /**
     * @param string $type
     * @return string|null|int
     */
    public function getCreatedAt($type = 'string') {
        switch($type) {
            case 'string' :
                $date = new Parser($this->createdAt);
                $createdAt = $date->format();
                break;
            case 'time' :
                $createdAt = (int) $this->createdAt;
                break;
            default :
                $createdAt = (int) $this->createdAt;
                break;
        }
        return $createdAt;
    }
}
