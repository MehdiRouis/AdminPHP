<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */

namespace App\Validators;

use Models\Database\PDOConnect;
use Models\Globals\Files;
use Models\Globals\Session;

/**
 * Class Verifications
 * @package App\Validators
 */
class Verifications {

    /**
     * @var PDOConnect
     */
    private $db;

    /**
     * @var array
     */
    private $inputTypes = [];

    /**
     * @var array
     */
    private $errorList = [];

    /**
     * @var bool|string
     */
    private $verification_table;

    /**
     * Verifications constructor.
     * @param bool|string $table
     */
    public function __construct($table = false) {
        $this->db = new PDOConnect();
        $this->verification_table = $table;
        $this->errorList = [];
        $this->inputTypes = [
            'username' => 'isValidUsername',
            'name' => 'isValidName',
            'birthDay' => 'isValidBirthDay',
            'adultBirthDay' => 'isAdult',
            'email' => 'isValidEmail',
            'phoneNumber' => 'isValidPhoneNumber',
            'title' => 'isValidTitle',
            'description' => 'isValidDescription',
            'rankId' => 'isValidRankId',
            'rankName' => 'isValidRankName',
            'icon' => 'isValidIcon',
            'color' => 'isValidColor',
            'image' => 'isValidPicture',
            'password' => 'isValidPassword',
            'captcha' => 'isValidCaptcha',
            'integer' => 'isValidInteger',
            'profileType' => 'isValidProfileType'
        ];
    }

    /**
     * Récupérer la table où auront lieu les vérifications si elle existe.
     * @return bool|string
     */
    public function getVerificationTable(): string {
        return $this->verification_table;
    }

    /**
     * Envoyer les vérifications des valeurs dans les fonctions prédéfinies.
     * @param string $valueType
     * @param array $content
     */
    public function verify($valueType, $content) {
        $found = false;
        $name = false;
        foreach($this->inputTypes as $key => $value) {
            if($key === $valueType) {
                $name = $key;
                $found = $value;
            }
        }
        foreach($content as $key => $value) {
            if($found) {
                if(isset($value[key($value)]) && !empty($value[key($value)])) {
                    $this->$found(key($value), $value[key($value)]);
                } else {
                    $inputsWithoutNullVerification = ['integer'];
                    if(isset($name) && !in_array($name, $inputsWithoutNullVerification)) {
                        $this->addError(key($value), 'Champ vide.');
                    }
                }
            } else {
                $this->addError(key($value), 'La clé "' . $valueType . '" de vérification est introuvable.');
            }
        }
    }

    /**
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidInteger($inputName, $inputValue): bool {
        if(intval($inputValue)) {
            return true;
        } else {
            $this->addError($inputName, 'Merci d\'entrer un nombre valide.');
        }
        return false;
    }

    /**
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidProfileType($inputName, $inputValue): bool {
       $validTypes = ['public', 'private'];
        if(in_array($inputValue, $validTypes)) {
            return true;
        }
        $this->addError($inputName, 'Erreur interne... Merci de réessayer plus tard.');
        return false;
    }
    /**
     * @param $inputName
     * @param $inputValue
     * @return bool
     */
    public function isValidUsername($inputName, $inputValue): bool {
        if(preg_match('/^[A-Za-zÂ-ÿ0-9-]+$/', $inputValue)) {
            if(strlen($inputName) > 3 && strlen($inputName) <= 15) {
                if($this->getVerificationTable()) {
                    if(!$this->db->existContent($this->getVerificationTable(), 'userName', $inputValue)) {
                        return true;
                    } else {
                        $this->addError($inputName, 'Le nom d\'utilisateur est déjà prit.');
                    }
                } else {
                    return true;
                }
            } else {
                $this->addError($inputName, 'Le champ doit contenir entre 3 et 15 caractères.');
            }
        } else {
            $this->addError($inputName, 'Caractères spéciaux non-autorisés.');
        }
        return false;
    }

    /**
     * Savoir si un prénom / nom est valide.
     * @param string$inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidName($inputName, $inputValue): bool {
        if(preg_match('/^[A-Za-zÂ-ÿ -]+$/', $inputValue)) {
            if(strlen($inputName) > 3 && strlen($inputName) <= 25) {
                return true;
            } else {
                $this->addError($inputName, 'Le champ doit contenir entre 3 et 25 caractères.');
            }
        } else {
            $this->addError($inputName, 'Caractères spéciaux non-autorisés.');
        }
        return false;
    }

    /**
     * Savoir si une date est valide.
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidDate($inputName, $inputValue): bool {
        if(preg_match('/^[\d]{4}\-[\d]{1,2}\-[\d]{1,2}$/', $inputValue)) {
            $date = explode('-', $inputValue);
            $day = (int)$date[2];
            $month = (int)$date[1];
            $year = (int)$date[0];
            if (checkdate($month, $day, $year)) {
                return true;
            } else {
                $this->addError($inputName, 'Date invalide.');
            }
        } else {
            $this->addError($inputName, 'Date demandée sous la forme : YYYY-MM-DD');
        }
        return false;
    }

    /**
     * Savoir si une date de naissance est valide.
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidBirthDay($inputName, $inputValue): bool {
        if($this->isValidDate($inputName, $inputValue)) {
            if($inputValue <= date('Y-m-d')) {
                return true;
            } else {
                $this->addError($inputName, 'Date de naissance invalide.');
            }
        }
        return false;
    }

    public function isAdult($inputName, $inputValue): bool {
        if($this->isValidDate($inputName, $inputValue)) {
            if($inputValue <= date('Y-m-d') || $inputValue >= date('Y-m-d', strtotime('-100 years'))) {
                if ($inputValue <= date('Y-m-d', strtotime('-18 years'))) {
                    return true;
                } else {
                    $this->addError($inputName, 'Vous devez avoir la majorité.');
                }
            } else {
                $this->addError($inputName, 'Date invalide.');
            }
        }
        return false;
    }

    /**
     * Savoir si une adresse mail est valide.
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    private function isBaseEmailValid($inputName, $inputValue): bool {
        if(filter_var($inputValue, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            $this->addError($inputName, 'Adresse email invalide.');
        }
        return false;
    }

    /**
     * Savoir si une adresse mail est valide avec des vérifications si il y a une table présente.
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidEmail($inputName, $inputValue): bool {
        if($this->isBaseEmailValid($inputName, $inputValue)) {
            if($this->getVerificationTable()) {
                if(!$this->db->existContent($this->getVerificationTable(), 'email', $inputValue)) {
                    return true;
                } else {
                    $this->addError($inputName, 'Adresse mail déjà utilisée.');
                }
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * Savoir si un numéro de téléphone est valide.
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidPhoneNumber($inputName, $inputValue): bool {
        if(preg_match('/^(\+33)[1-9]([0-9]{2}){4}$/i', $inputValue)) {
            if($this->getVerificationTable()) {
                if(!$this->db->existContent($this->getVerificationTable(), 'phoneNumber', $inputValue)) {
                    return true;
                } else {
                    $this->addError($inputName, 'Numéro de téléphone déjà utilisé.');
                }
            } else {
                return true;
            }
        } else {
            $this->addError($inputName, 'Numéro demandé sous la forme +33101010101');
        }
        return false;
    }

    /**
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidTitle($inputName, $inputValue): bool {
        if(preg_match('/^[a-zA-ZÂ-ÿ -!:.,+=\'"*0-9]+$/', $inputValue)) {
            if(strlen($inputValue) > 5 && strlen($inputValue) < 30) {
                return true;
            } else {
                $this->addError($inputName, 'La valeur doit contenir entre 5 et 30 caractères.');
            }
        } else {
            $this->addError($inputName, 'Ce champ contient des caractères spéciaux non pris en charge.');
        }
        return false;
    }

    /**
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidDescription($inputName, $inputValue): bool {
        if(preg_match('/^[a-zA-ZÂ-ÿ -!$€:().\'",*+=0-9 \n \r]+$/', $inputValue)) {
            if(strlen($inputValue) > 50 && strlen($inputValue) < 1000) {
                return true;
            } else {
                $this->addError($inputName, 'Le champ doit contenir entre 50 et 1000 caractères.');
            }
        } else {
            $this->addError($inputName, 'Ce champ contient des caractères spéciaux non pris en charge.');
        }
        return false;
    }

    /**
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidRankId($inputName, $inputValue) {
        if($this->db->existContent('alive_users_ranks', 'id', $inputValue)) {
            return true;
        }
        $this->addError('global', 'Erreur interne... Merci de réessayer plus tard.');
        return false;
    }

    /**
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidRankName($inputName, $inputValue) {
        if(preg_match('/^[a-zA-ZÂ-ÿ ]+$/', $inputValue)) {
            if(strlen($inputValue) > 3 && strlen($inputValue) < 50) {
                return true;
            } else {
                $this->addError($inputName, 'Le champ doit contenir entre 3 et 50 caractères.');
            }
        } else {
            $this->addError($inputName, 'Le champ contient des caractères non-autorisés.');
        }
        return false;
    }

    public function isValidIcon($inputName, $inputValue) {
        if(preg_match('/^[a-zA-Z \-\#\.\_]+$/', $inputValue)) {
            if(strlen($inputValue) < 50) {
                return true;
            } else {
                $this->addError($inputName, 'Le champ doit contenir moins de 50 caractères.');
            }
        } else {
            $this->addError($inputName, 'Le champ contient des caractères non-autorisés.');
        }
        return false;
    }

    public function isValidColor($inputName, $inputValue) {
        if(preg_match('/^[a-zA-Z0-9\-\_\.\#]+$/', $inputValue)) {
            if(strlen($inputValue) < 50) {
                return true;
            } else {
                $this->addError($inputName, 'Le champ doit contenir moins de 50 caractères.');
            }
        } else {
            $this->addError($inputName, 'Le champ contient des caractères non-autorisés.');
        }
    }

    public function isValidPicture($inputName) {
        $SIZE_MAX = 2000000;
        $files = new Files();
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if(in_array($files->getExtension($inputName), $validExtensions)) {
            $validMimeTypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/png'];
            if(in_array($files->getMimeType($inputName), $validMimeTypes)) {
                if($files->getValue($inputName)['size'] <= $SIZE_MAX) {
                    return $this->getErrors();
                } else {
                    $this->addError($inputName, 'La taille du fichier ne doit pas dépasser 2Mo.');
                }
            } else {
                $this->addError($inputName, 'Seul les images sont acceptées (jpg / png / gif).');
            }
        } else {
            $this->addError($inputName, 'Seul les images sont acceptées (jpg / png / gif).');
        }
        return $this->getErrors();
    }

    /**
     * Savoir si un mot de passe est valide.
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidPassword($inputName, $inputValue): bool {
        if(strlen($inputValue) > 6) {
            return true;
        } else {
            $this->addError($inputName, 'Le mot de passe doit contenir plus de 6 caractères.');
        }
        return false;
    }

    /**
     * @param string $inputName
     * @param string $inputValue
     * @return bool
     */
    public function isValidCaptcha($inputName, $inputValue): bool {
        $session = new Session();
        if(strtoupper($inputValue) === $session->getValue('captcha')) {
            return true;
        }
        $this->addError($inputName, 'Captcha invalide.');
        return false;
    }

    /**
     * Ajouter une erreur.
     * @param string $inputName
     * @param string $message
     */
    public function addError($inputName, $message) {
        $this->errorList[$inputName] = $message;
    }

    /**
     * Récupérer les erreurs.
     * @return array
     */
    public function getErrors(): array {
        return $this->errorList;
    }

    public function __destruct() {

    }

}
