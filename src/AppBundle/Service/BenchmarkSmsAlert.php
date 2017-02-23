<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 21.02.2017
 * Time: 21:59
 */

namespace AppBundle\Service;


class BenchmarkSmsAlert
{
    protected $gateway;
    protected $phoneNumber;

    public function __construct(SmsGateway $gateway, $phoneNumber){
        $this->gateway = $gateway;
        $this->phoneNumber = $phoneNumber;
    }

    public function sendAlert($message = '')
    {
        return $this->gateway->sendSms($this->phoneNumber, $message);
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }


}