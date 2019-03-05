<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */

namespace Models\Users;

use App\Date\Parser;
use App\Protections\Security;
use App\Validators\Errors;
use Models\Authentication\DBAuth;
use Models\Database\PDOConnect;
use Models\Globals\Post;
use Models\Globals\Session;
use Models\Keys\Key;

/**
 * Class User
 * @package Models\Users
 */
class User extends Session {

    /**
     * @var PDOConnect
     */
    private $db;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var DBAuth
     */
    private $dbauth;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var int
     */
    private $phoneNumber;

    /**
     * @var string
     */
    private $birthDay;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * @var int
     */
    private $rank;

    /**
     * @var int
     */
    private $shopPoints;

    /**
     * @var int
     */
    private $createdAt;

    /**
     * @var string
     */
    private $profileType;

    private $profileBanner;

    /**
     * User constructor.
     * @param false|string $value
     * @param string $searchType
     */
    public function __construct($value = false, $searchType = 'id') {
        parent::__construct();
        $this->db = new PDOConnect();
        $this->security = new Security();
        $this->dbauth = new DBAuth();
        if($value) {
            $req = $this->db->query("SELECT * FROM alive_users WHERE {$searchType} = ?", [$value]);
            if ($req->rowCount() > 0) {
                $user = $req->fetch();
            }
        } else {
            if($this->dbauth->isLogged()) {
                $user = $this->getValue('auth');
            }
        }
        if(isset($user)) {
            $this->id = $user->id;
            $this->userName = $user->userName;
            $this->lastName = $user->lastName;
            $this->firstName = $user->firstName;
            $this->phoneNumber = $user->phoneNumber;
            $this->birthDay = $user->birthDay;
            $this->password = $user->password;
            $this->rank = $user->rank;
            $this->email = $user->email;
            $this->shopPoints = $user->shopPoints;
            $this->createdAt = $user->createdAt;;
            $this->profileType = $user->profile_type;
            $this->profileBanner = $user->profile_banner;
        }
    }

    /**
     * Création de la session à partir de l'instance.
     */
    public function createSession() {
        $this->setValue('auth', $this->serialize());
        $this->setValue('token', $this->security->getUniqueToken());
    }

    /**
     * Retourne toutes les valeurs d'un utilisateur à partir de l'id sous la form fetch
     * @return mixed
     */
    private function serialize() {
        $req = $this->db->query('SELECT * FROM alive_users WHERE id = ?', [$this->getId()]);
        return $req->fetch();
    }

    /**
     * @param string $userName
     * @param string $accountType
     * @param string $lastName
     * @param string $firstName
     * @param string $email
     * @param string $phoneNumber
     * @param string $birthDay
     * @param string $password
     * @param bool $session
     */
    public function add($userName, $accountType, $lastName, $firstName, $email, $phoneNumber, $birthDay, $password, $session = false) {
        $password = $this->security->hash($password);
        $this->db->query('INSERT INTO alive_users (userName, lastName, firstName, phoneNumber, birthDay, password, `rank`, email, shopPoints, createdAt) VALUES (?,?,?,?,?,?,?,?,?,?)', [
            $userName, $lastName, $firstName, $phoneNumber, $birthDay, $password, $accountType, $email, 0, time()
        ]);
        if($session) {
            $req = $this->db->query('SELECT id FROM alive_users WHERE userName = ? AND email = ? AND password = ?', [$userName, $email, $password]);
            if($req->rowCount() > 0) {
                $fetch = $req->fetch();
                $user = new User($fetch->id);
                $user->createSession();
                $this->security->safeLocalRedirect('dashboard');
            }
        }
    }


    /**
     * @param string $userName
     * @param string $lastName
     * @param string $firstName
     * @param int $rankId
     * @param string $email
     * @param string $phoneNumber
     * @param string $birthDay
     * @param string $password
     * @param int $shopPoint
     * @param string $profileType
     * @return \PDOStatement
     */
    public function editAll($userName, $lastName, $firstName, $rankId, $email, $phoneNumber, $birthDay, $shopPoint, $profileType) {
        $shopPoint = $shopPoint ? $shopPoint : 0;
        $req = $this->db->query('UPDATE alive_users SET userName = ?, lastName = ?, firstName = ?, `rank` = ?, email = ?, phoneNumber = ?, birthDay = ?, shopPoints = ?, profile_type = ? WHERE id = ?',
            [$userName, $lastName, $firstName, $rankId, $email, $phoneNumber, $birthDay, $shopPoint, $profileType, $this->getId()]);
        return $req;
    }

    /**
     * Restreindre l'accès à des droits spécifique.
     * @param $permissionName
     */
    public function restrict($permissionName) {
        $this->security->restrict();
        if(!$this->hasRight($permissionName)) {
            $this->security->safeLocalRedirect('default');
        }
    }

    /**
     * @param string $order
     * @param bool|int $limit
     * @return array
     */
    public function getUsers($order = '', $limit = false) {
        $order = $order ? ' ' . $order : '';
        $limit = $limit ? ' LIMIT ' . $limit : '';
        $req = $this->db->query('SELECT id FROM alive_users' . $order . $limit);
        $users = [];
        while($user = $req->fetch()) {
            $users[] = new User($user->id);
        }
        return $users;
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
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param $value
     * @return string
     */
    private function getInitial($value): ?string {
        return $value[0] === '&' ? substr($value, 0, strpos($value, ';')+strlen(';')) : strtoupper($value[0]);
    }

    /**
     * @return string
     */
    public function getInitialUserName(): ?string {
        return $this->getInitial($this->getUserName());
    }

    /**
     * @return string
     */
    public function getInitialFirstName(): ?string {
        return $this->getInitial($this->getFirstName());
    }

    /**
     * @return string
     */
    public function getInitialLastName(): ?string {
        return $this->getInitial($this->getLastName());
    }

    /**
     * @return string
     */
    public function getInitialFullName(): ?string {
        return $this->getInitial($this->getFirstName()) . ' ' . $this->getInitial($this->getLastName());
    }

    /**
     * @return string
     */
    public function getFullName(): ?string {
        return strtoupper($this->getLastName()) . ' ' . $this->getFirstName();
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @var string|int $phoneNumber
     * @return \PDOStatement
     */
    public function setPhoneNumber($phoneNumber): \PDOStatement {
        $req = $this->db->query('UPDATE alive_users SET phoneNumber = ? WHERE id = ?', [$phoneNumber, $this->getId()]);
        return $req;
    }

    /**
     * @return bool
     */
    public function isPhoneNumberValidate(): bool {
        $req = $this->db->query('SELECT id FROM alive_keys WHERE userId = ? AND `value` = ? AND status = ?', [$this->getId(), $this->getPhoneNumber(), 2]);
        return $req->rowCount() > 0 ? true : false;
    }

    /**
     * @return string
     */
    public function getBirthDay($format = 'd/m/Y'): ?string
    {
        return date($format, strtotime($this->birthDay));
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function matchPassword($password): ?bool {
        return password_verify($password, $this->getPassword());
    }

    /**
     * @param string $password
     * @return \PDOStatement
     */
    public function setPassword($password): \PDOStatement {
        $newpass = $this->security->hash($password);
        $req = $this->db->query('UPDATE alive_users SET password = ? WHERE id = ?', [$newpass, $this->getId()]);
        return $req;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return \PDOStatement
     */
    public function setEmail($email): \PDOStatement {
        $req = $this->db->query('UPDATE alive_users SET email = ? WHERE id = ?', [$email, $this->getId()]);
        return $req;
    }

    /**
     * @return bool
     */
    public function isEmailValidate(): bool {
        $req = $this->db->query('SELECT id FROM alive_keys WHERE userId = ? AND `value` = ? AND status = ?', [$this->getId(), $this->getEmail(), 2]);
        return $req->rowCount() > 0 ? true : false;
    }

    /**
     * @return Rank
     */
    public function getRank(): Rank
    {
        return new Rank($this->rank);
    }

    /**
     * @return int|null
     */
    public function getShopPoints(): ?int
    {
        return $this->shopPoints;
    }

    /**
     * @param string $permission
     * @param string $searchType
     * @return bool
     */
    public function hasRight($permission, $searchType = 'name'): bool
    {
        $permission = new Permission($permission, $searchType);
        return $permission->hasRight($this->getRank()->getId());
    }

    /**
     * @return string
     */
    public function getProfileLink(): ?string {
        return $GLOBALS['router']->getFullUrl('profile', ['id' => $this->getId()]);
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string {
        $date = new Parser($this->createdAt);
        return $date->format();
    }

    /**
     * @param bool $toText
     * @return string
     */
    public function getProfileType($toText = false) {
        if($toText) {
            $profileType = $this->profileType === 'public' ? 'Publique' : 'Privé';
        } else {
            $profileType = $this->profileType;
        }
        return $profileType;
    }

    /**
     * @param string $profileType
     * @return \PDOStatement
     */
    public function setProfileType($profileType) {
        $req = $this->db->query('UPDATE alive_users SET profile_type = ? WHERE id = ?', [$profileType, $this->getId()]);
        return $req;
    }

    public function isProfilePublic() {
        return $this->getProfileType() === 'public' ? true : false;
    }

    /**
     * @param bool $court
     * @return null|string
     */
    public function getProfileBanner($court = false) {
        if($court) {
            $banner = $this->profileBanner;
        } else {
            $banner = !is_null($this->profileBanner) ? WEBSITE_LINK . '/public/assets/img/profile/banners/' . $this->profileBanner : WEBSITE_LINK . '/public/assets/img/carousel/sky.jpg';
        }
        return $banner;
    }

    /**
     * @param string $newBanner
     * @return \PDOStatement
     */
    public function setProfileBanner($newBanner) {
        $req = $this->db->query('UPDATE alive_users SET profile_banner = ? WHERE id = ?', [$newBanner, $this->getId()]);
        return $req;
    }

    /**
     * @return bool
     */
    public function isProfileBannerNull() {
        return is_null($this->profileBanner);
    }

    public function matchCSRFToken($csrf) {
        if($this->getCSRFToken() !== $csrf) {
            $this->security->safeLocalRedirect('default');
        }
    }

    /**
     * @return int
     */
    public function countCreatedProjects(): ?int {
        $projects = new Projects($this->getId());
        return $projects->countCreatedProjects();
    }

    /**
     * @return int
     */
    public function countAllProjects(): ?int {
        $projects = new Projects($this->getId());
        return $projects->countAllProjects();
    }

    /**
     * @return int|null
     */
    public function countFinishedProjects(): ?int {
        $projects = new Projects($this->getId());
        return $projects->countFinishedProjects();
    }

    /**
     * @return array|null
     */
    public function getAllCreatedProjects(): ?array {
        $projects = new Projects($this->getId());
        return $projects->getAllCreatedProjects();
    }

    /**
     * @return string|null
     */
    public function getCSRFToken(): ?string {
        $token = $this->security->getValue('token');
        return isset($token) ? $token : false;
    }

    public function generateKey($type, $status, $value = false) {
        $keys = new Keys($this->getId());
        return $keys->addKey($type, $status, $value);
    }

    public function generateSMSKey($type, $status, $value = null) {
        $keys = new Keys($this->getId());
        return $keys->generateSMSKey($type, $status, $value);
    }

    public function validateEmail($inputKey) {
        $post = new Post();
        $errors = new Errors();
        if ($post->getValue($inputKey)) {
            $req = $this->db->query('SELECT id FROM alive_keys WHERE code = ? AND userId = ? AND type = ? AND status = ?', [$post->getValue($inputKey), $this->getId(), 1, 1]);
            if($req->rowCount() > 0) {
                $keyId = $req->fetch();
                $key = new Key($keyId->id);
                $key->setStatus(2);
                return $errors->getErrors();
            } else {
                $errors->setError($inputKey, 'Clé introuvable.');
            }
        } else {
            $errors->setError($inputKey, 'Clé introuvable.');
        }
        return $errors->getErrors();
    }

    public function validatePhoneNumber($inputKey) {
        $post = new Post();
        $errors = new Errors();
        if ($post->getValue($inputKey)) {
            $req = $this->db->query('SELECT id FROM alive_keys WHERE code = ? AND userId = ? AND type = ? AND status = ?', [$post->getValue($inputKey), $this->getId(), 2, 1]);
            if($req->rowCount() > 0) {
                $keyId = $req->fetch();
                $key = new Key($keyId->id);
                $key->setStatus(2);
                return $errors->getErrors();
            } else {
                $errors->setError($inputKey, 'Clé introuvable.');
            }
        } else {
            $errors->setError($inputKey, 'Clé introuvable.');
        }
        return $errors->getErrors();
    }

    public function getLowerRanks() {
        $req = $this->db->query('SELECT id FROM alive_users_ranks WHERE id <= ?', [$this->getRank()->getId()]);
        $ranks = [];
        while($rank = $req->fetch()) {
            $ranks[] = new Rank($rank->id);
        }
        return $ranks;
    }

    /**
     * @return \PDOStatement
     */
    public function delete() {
        $req = $this->db->query('DELETE FROM alive_users WHERE id = ?', [$this->getId()]);
        return $req;
    }
}