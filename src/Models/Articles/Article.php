<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 14:00
 */

namespace Models\Articles;

use App\Date\Parser;
use Models\Database\PDOConnect;
use Models\Users\User;

/**
 * Class Article
 * @package Models\Articles
 */
class Article {

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
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $createdAt;

    /**
     * @var int
     */
    private $createdBy;

    public function __construct($value = false, $searchType = 'id') {
        $this->db = new PDOConnect();
        if($value) {
            $req = $this->db->query("SELECT * FROM alive_news WHERE {$searchType} = ?", [$value]);
            if ($req->rowCount() > 0) {
                $new = $req->fetch();
                $this->id = $new->id;
                $this->title = $new->title;
                $this->description = $new->description;
                $this->content = $new->content;
                $this->createdAt = $new->createdAt;
                $this->createdBy = $new->createdBy;
            }
        }
    }

    /**
     * @param bool $limit
     * @return array
     */
    public function getAllNews($limit = false): array {
        $sql = 'SELECT id FROM alive_news';
        if($limit) {
            $sql = 'SELECT id FROM alive_news LIMIT '. $limit;
        }
        $req = $this->db->query($sql);
        $news = [];
        while($new = $req->fetch()) {
            $news[] = new Article($new->id);
        }
        return $news;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        $date = new Parser($this->createdAt);
        return $date->format();
    }

    /**
     * @return User
     */
    public function getCreatedBy(): User
    {
        return new User($this->createdBy);
    }

}