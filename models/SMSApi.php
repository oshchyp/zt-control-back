<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 02.11.2018
 * Time: 12:57
 */

namespace app\models;


use yii\base\Component;

class SMSApi extends Component
{

    /**
     * @var string
     */
    private $login = 'oshchyp';

    /**
     * @var string
     */
    private $password = 'q27031605w';

    /**
     * @var string
     */
    private $sender = 'ZT LOGIC';

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $number;

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }


    /**
     * @return mixed
     */
    public function sendSMS()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://smsc.ua/sys/send.php?login='.$this->login.'&psw='.$this->password.'&charset=utf-8&phones='.$this->number.'&mes='.$this->text.'&sender='.$this->sender);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * @param $number
     * @param $text
     * @param array $config
     * @return mixed
     */
    public static function send($number,$text,$config=[]){
        $instance = new static($config);
        $instance->setNumber($number);
        $instance->setText($text);
        return $instance->sendSMS();
    }


}