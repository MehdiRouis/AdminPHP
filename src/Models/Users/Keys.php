<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 07/02/19
 * Time: 08:32
 */

namespace Models\Users;

use App\Protections\Security;
use Models\Database\PDOConnect;
use Models\Keys\Key;

/**
 * Class Keys
 * @package Models\Users
 */
class Keys {

    /**
     * @var PDOConnect
     */
    private $db;

    /**
     * @var array
     */
    private $keys;

    public function __construct($userId) {
        $this->db = new PDOConnect();
        $this->userId = $userId;
    }

    public function getUserKeys() {
        $req = $this->db->query('SELECT id FROM alive_keys WHERE userId = ?', [$this->userId]);
        while($key = $req->fetch()) {
            $this->keys[] = new Key($key->id);
        }
    }

    public function addKey($type, $status, $value = false) {
        $key = new Key();
        return $key->generate($type, $this->userId, $status, $value);
    }

    public function generateSMSKey($type, $status, $value = null) {
        $security = new Security();
        $keygen = $security->generateRandomString(5, '0123456789');
        $key = new Key();
        return $key->generate($type, $this->userId, $status, $value, $keygen);
    }

}
