<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 21.02.2017
 * Time: 21:52
 */

namespace AppBundle\Service;


class SmsGateway
{
    protected $apiUrl;
    protected $userName;
    protected $userPassword;

    public function __construct($apiUrl, $userName, $userPassword)
    {
        $this->apiUrl = $apiUrl;
        $this->userName = $userName;
        $this->userPassword = $userPassword;
    }

    public function sendSms($number, $message = '')
    {
        return $this->callApi(
            $this->apiUrl . 'sendsms/number/' . urldecode($number) . '/msg/' . urlencode($message),
            $this->userName,
            $this->userPassword
        );
    }

    public function callApi($apiAction, $user, $password)
    {
        //cURL request using given parameters
        return 1;
    }
}