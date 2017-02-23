<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 20.02.2017
 * Time: 21:59
 */

namespace AppBundle\Service;

use Swift_Message;

class BenchmarkAlert
{
    protected $mailer;
    protected $twig;
    protected $to;
    protected $from;
    protected $subject;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, array $params){
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->from = $params['from'];
        $this->to = $params['to'];
        $this->subject = $params['subject'];
    }

    public function sendAlert($benchmarkedUrl = '')
    {
        //Setup the message
        $message = Swift_Message::newInstance()
            ->setSubject($this->subject)
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setBody($this->twig-> render(
                'benchmark/alert.html.twig',
                array('benchmarkedUrl' => $benchmarkedUrl)
            ), 'text/html');

        //Send the message
        return $this->mailer->send($message);
    }

}