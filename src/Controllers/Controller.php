<?php
/**
 * Created by PhpStorm.
 * User: esska
 * Date: 23/01/19
 * Time: 07:59
 */

namespace Controllers;

use App\Routes\Router;
use App\Views\EmailTemplating;
use App\Views\Navbar;
use App\Views\View;
use App\Protections\Security;
use Models\Authentication\DBAuth;
use Models\Globals\Session;
use Models\Users\User;

/**
 * Class Controller
 * @package Controllers
 */
class Controller {

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Security
     */
    protected $security;

    /**
     * @var DBAuth
     */
    protected $dbauth;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var \Swift_SmtpTransport
     */
    protected $mail;

    /**
     * @var \App\SMS\Sender
     */
    protected $sms;

    /**
     * Controller constructor.
     */
    public function __construct() {
        $this->session = new Session();
        $this->security = new Security();
        $this->dbauth = new DBAuth();
        $this->user = new User();
        if($this->user->getId()) {
            $this->user->updateSession();
        }
        //$transport = (new \Swift_SendmailTransport('/usr/sbin/sendmail -bs'));
        $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))->setUsername('email@host.domain')->setPassword('password');
        $this->mail = new \Swift_Mailer($transport);
        $this->sms = new \App\SMS\Sender('esskafr', 'apiKey :3');
    }

    /**
     * @param string $subject
     * @param array $to
     * @param string $message
     * @return int
     */
    protected function sendMail($subject, $to, $message) {
        $templating = new EmailTemplating();
        $message = (new \Swift_Message($subject))->setFrom(['stuuf.kdev@gmail.com' => 'AliveWebProject'])->setTo($to)->setBody($templating->getEmailTemplate($message), 'text/html', 'utf-8');
        return $this->mail->send($message);
    }

    /**
     * @return Router
     */
    protected function getRouter(): Router {
        return $GLOBALS['router'];
    }

    /**
     * Permet de rendre une vue ( Chemin : [/public/views/]...[.php]
     * @param string $path
     * @param array $args
     * @throws \Exception \App\Views\ViewsExceptions
     */
    protected function render($path, $args = [])
    {
        $args['scripts'] = isset($args['scripts']) ? $args['scripts'] : [];
        $args['router'] = $this->getRouter();
        $args['navbar'] = new Navbar();
        $args['errors'] = isset($args['errors']) ? $args['errors'] : [];
        $args['auth'] = $this->dbauth;
        if ($this->dbauth->isLogged()) {
            $args['user'] = $this->user;
        }
        $headerTpl = isset($args['headerTpl']) ? $args['headerTpl'] : 'templates/headerBase';
        $footerTpl = isset($args['footerTpl']) ? $args['footerTpl'] : 'templates/footerBase';
        if($headerTpl) {
            $header = new View($headerTpl);
            $header->assign($args);
        }
        $view = new View($path);
        $view->assign($args);
        if($footerTpl) {
            $footer = new View($footerTpl);
            $footer->assign($args);
        }
    }

    public function __destruct() {

    }

}
