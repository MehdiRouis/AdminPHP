<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */

namespace App\Validators;

use App\Protections\Security;
use Models\Authentication\DBAuth;
use Models\Globals\Post;
use Models\Globals\Session;

/**
 * Class Validator
 * @package App\Validators
 */
class Validator extends Security {

    /**
     * @var Post
     */
    private $post;

    /**
     * @var bool
     */
    private $table;

    /**
     * @var array
     */
    private $postValues = [];

    /**
     * @var array
     */
    private $verifiedInputs = [];

    /**
     * Validator constructor.
     * @param array $postValues
     * @param bool|string $table
     */
    public function __construct($postValues = [], $table = false) {
        parent::__construct();
        $this->post = new Post();
        $this->table = $table;
        $this->postValues = $postValues;
        $newArray = [];
        foreach($this->postValues as $valueType => $valueArray) {
            $newArray[$valueType] = [];
            foreach($valueArray as $postName) {
                $newArray[$valueType][] = [$postName => $this->post->getValue($postName)];
            }
        }
        $this->postValues = $newArray;
    }

    /**
     * Valider les informations des inputs via la classe Vérifications
     * @return array
     */
    public function validate(): array {
        $postValues = $this->postValues;
        $validType = new Verifications($this->table);
        foreach ($postValues as $valueType => $valuePost) {
            $validType->verify($valueType, $valuePost);
            $this->verifiedInputs = $validType->getErrors();
        }
        $dbauth = new DBAuth();
        if($dbauth->isLogged()) {
            $session = new Session();
            if($session->getValue('token') !== $this->post->getValue('CSRFToken')) {
                $this->addError('global', 'Une erreur est survenue... Merci de réessayer plus tard.');
            }
        }
        return $this->getErrors();
    }

    /**
     * Obtenir les erreurs des vérifications
     * @return array
     */
    public function getErrors(): array {
        return $this->verifiedInputs;
    }

    /**
     * Ajouter une erreur à la liste
     * @param string$inputName
     * @param string $errorValue
     */
    public function addError($inputName, $errorValue) {
        $this->verifiedInputs[$inputName] = $errorValue;
    }
    /**
     * Savoir s'il y a des erreurs.
     * @return bool
     */
    public function isThereErrors(): bool {
        if(count($this->verifiedInputs) > 0) {
            return true;
        } else {
            return false;
        }
    }
}