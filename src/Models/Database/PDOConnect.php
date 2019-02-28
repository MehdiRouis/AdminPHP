<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */
namespace Models\Database;

use \PDO;
use \Exception;

/**
 * Class PDOConnect
 * @package Models\Database
 */
class PDOConnect {

    /**
     * Instance de PDO
     * @var PDO
     */
    private $pdo;

    /**
     * L'hôte de la base de donnée
     * @var string
     */
    private $db_host;

    /**
     * Nom d'utilisateur de la base de donnée
     * @var string
     */
    private $db_user;

    /**
     * Mot de passe de la base de donnée
     * @var string
     */
    private $db_pass;

    /**
     * Nom de la base de donnée
     * @var string
     */
    private $db_name;

    /**
     * Stockage des données dans les attributs de la classe.
     * @param string $db_name
     * @param string $db_host
     * @param string $db_user
     * @param string $db_pass
     */
    public function __construct($db_name = 'alivewebproject', $db_host = 'localhost', $db_user = 'root', $db_pass = '') {
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;
    }

    /**
     * Récupérer l'instance de PDO
     * @return PDO
     */
    public function getPDO(): PDO {
        if($this->pdo === null) {
            try {
                $pdo = new PDO('mysql:dbname=' . $this->db_name . ';host=' . $this->db_host . ';charset=utf8', $this->db_user, $this->db_pass);
                $this->pdo = $pdo;
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            } catch(Exception $ex) {
                exit('Erreur de connexion à la base de donnée... > '. $ex);
            }
        }
        return $this->pdo;
    }

    /**
     * Exécuter une requête PDO "query" s'il n'y a pas de paramètres et prepare / execute s'il y en a.
     * @param string $statement
     * @param bool|array $parameters
     * @return \PDOStatement
     */
    public function query($statement, $parameters = false): \PDOStatement {
        if($parameters) {
            $req = $this->getPDO()->prepare($statement);
            $req->execute($parameters);
        } else {
            $req = $this->getPDO()->query($statement);
        }
        return $req;
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $value
     * @return bool
     */
    public function existContent($table, $column, $value): bool {
        $req = $this->query("SELECT id FROM {$table} WHERE {$column} = ?", [$value]);
        return $req->rowCount() > 0 ? true : false;
    }

    public function __destruct() {

    }

}