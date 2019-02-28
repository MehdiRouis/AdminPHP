<?php
/**
 * Copyright (c) 2019. Tous droit réservé.
 */

/**
 * Created by PhpStorm.
 * User: esska
 * Date: 04/02/19
 * Time: 10:47
 */
namespace App\SMS;

/**
 * Class Sender
 * @package App\SMS
 */
class Sender {

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $sender = 'Alive';

    /**
     * @var int
     */
    private $phoneNumber;

    /**
     * @var array
     */
    private $fields = [];

    /**
     * Sender constructor.
     * @param string $login
     * @param string $apiKey
     */
    public function __construct($login, $apiKey) {
        $this->apiUrl = 'https://api.allmysms.com/http/9.0/sendSms/';
        $this->fields['login'] = $login;
        $this->fields['apiKey'] = $apiKey;
    }

    public function definePhoneNumber($phoneNumber) {
        $phoneNumber = str_replace('+', '', $phoneNumber);
        $this->phoneNumber = $phoneNumber;
    }

    public function defineMessage($message) {
        $this->fields['smsData'] = "
        <DATA>
            <MESSAGE><![CDATA[{$message}]]></MESSAGE>
            <TPOA>{$this->sender}</TPOA>
            <SMS>
                <MOBILEPHONE>{$this->phoneNumber}</MOBILEPHONE>
            </SMS>
        </DATA>";
    }

    public function send() {
        $fieldsString = http_build_query($this->fields);
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
            curl_setopt($ch, CURLOPT_POST, count($this->fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);    // permet d’éviter le temps d'attente par défaut : 300 sec - optionnel
            curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 1024); // limite de detection des connexions lentes, en octets/sec (ici : 1 ko) - optionnel
            curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, 1);     // coupe la connexion si en dessous de CURLOPT_LOW_SPEED_LIMIT pendant plus de CURLOPT_LOW_SPEED_TIME - optionnel

            $result = curl_exec($ch);

            echo $result;
            curl_close($ch);


        } catch (\Exception $e) {
            echo 'Le message n\'a pas pu être envoyé. Merci de réessayer...';
        }
    }

}