<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */

namespace App\Protections;
use Models\Authentication\DBAuth;
use Models\Database\PDOConnect;
use Models\Globals\Session;

/**
 * Class Security
 * @package App\Protections
 */
class Security extends Session {

    /**
     * @var PDOConnect
     */
    private $db;

    /**
     * Vérifier les injections SQL dans les paramètres de l'URL ( $_GET )
     */
    public function __construct() {
        parent::__construct();
        $injection = 'INSERT|UNION|SELECT|NULL|COUNT|FROM|LIKE|DROP|TABLE|WHERE|COUNT|COLUMN|TABLES|INFORMATION_SCHEMA|OR';
        foreach ($_GET as $getSearchs) {
            $getSearch = explode(' ', $getSearchs);
            foreach ($getSearch as $k => $v) {
                if (in_array(strtoupper(trim($v)), explode('|', $injection))) {
                    exit;
                }
            }
        }
        $this->db = new PDOConnect();
    }

    /**
     * Rediriger via le nom d'une route.
     * @param string $routeName
     * @param array $params
     * @param bool $exit
     */
    public function safeLocalRedirect($routeName, $params = [], $exit = true) {
        $extra = '';
        if(strpos($routeName, '|')) {
            $route = explode('|', $routeName);
            $routeName = $route[0];
            $extra = $route[1];
        }
        $updatedLink = $GLOBALS['router']->getFullUrl($routeName, $params);
        $this->safeExternalRedirect($updatedLink . $extra, $exit);
    }

    /**
     * Redirection sécurisée
     * @param string $link
     * @param bool $exit
     */
    public function safeExternalRedirect($link, $exit = true) {
        if (!headers_sent()) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $link);
            header('Connection: close');
        }
        print '<html lang="fr">';
        print '<head><title>Redirection...</title>';
        print "<meta http-equiv='Refresh' content='0;url=' {$link}' />";
        print '</head>';
        print "<body onload='location.replace('{$link}')'>";
        print 'Vous rencontrez peut-être un problème.<br />';
        print "<a href='{$link}'>Se faire rediriger</a><br />";
        print 'Si ceci est une erreur, merci de cliquer sur le lien.<br />';
        print '</body>';
        print '</html>';
        if ($exit) {
            exit;
        }
    }

    /**
     * Afficher des variables à sécuriser
     * @param string $text
     * @return string
     */
    public function parse($text): string {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * @return string
     */
    public function getUniqueToken(): string {
        return md5(uniqid(rand() * time(), TRUE));
    }

    /**
     * @param int $width
     * @param int $height
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return string
     */
    public function generateCaptcha($width = 154, $height = 34, $red = 255, $green = 255, $blue = 255): string {
        $text = $this->generateCaptchaCode();
        $img = imagecreate($width, $height);
        imagecolorallocate($img, $red, $green, $blue);
        $textcolor = imagecolorallocate($img, 10, 10, 10);
        imagettftext($img, 25, 0, 0, 30, $textcolor, PROJECT_LIBS . '/public/assets/fonts/captcha.ttf', $text);
        ob_start();
        imagepng($img);
        $imagedata = ob_get_contents();
        ob_end_clean();
        $imagedata = base64_encode($imagedata);
        return "<img src='data:image/png;base64,{$imagedata}' width='100%' height='auto' alt='Image'/>";
    }

    /**
     * @return string
     */
    public function generateCaptchaCode(): string {
        $captchaString = $this->generateRandomString(7, '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZ');
        $this->setValue('captcha', $captchaString);
        return $captchaString;
    }

    public function generateRandomKey() {
        return $this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4);
    }

    /**
     * @param int $length
     * @param string $characters
     * @return string
     */
    public function generateRandomString($length, $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $randomString = '';
        for($i = 0;$i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * @param string $password
     * @return string
     */
    public function hash($password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param bool $mustBeLogged
     */
    public function restrict($mustBeLogged = true) {
        $dbauth = new DBAuth();
        if($mustBeLogged && !$dbauth->isLogged() || !$mustBeLogged && $dbauth->isLogged()) {
            if($GLOBALS['router']->getActualRoute() === 'home') {
                $this->safeLocalRedirect('dashboard');
            } else {
                $this->safeLocalRedirect('default');
            }
        }
    }

    /**
     * @param int|string $id
     * @param string $table
     * @return bool
     */
    public function idVerification($id, $table): bool {
        $req = $this->db->query("SELECT id FROM {$table} WHERE id = ?", [$id]);
        return $req->rowCount() > 0 ? true : false;
    }

    /**
     * @param string $value
     * @return string
     */
    public function secureValue($value): string {
        return htmlentities($value);
    }

    public function __destruct() {

    }

}
