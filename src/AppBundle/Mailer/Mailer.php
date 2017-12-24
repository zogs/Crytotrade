<?php

namespace AppBundle\Mailer;

use Symfony\Component\Templating\EngineInterface;
use AppBundle\Entity\ArbitrageProspective;

class Mailer
{
    protected $mailer;
    protected $templating;
    protected $expediteur;
    protected $admins;
    protected $server;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, $sender, $admins)
    {
        $this->mailer = $mailer;
        $this->expediteur = $sender;
        $this->templating = $templating;
        $this->admins = $admins;
    }

    public function sendTestMessage($email)
    {
        return $this->sendMessage($this->expediteur, $email, 'test mailer', '<html><body><strong>Hello world</strong></body></html>');
    }

    public function sendHighArbitrageProspective(ArbitrageProspective $prospect)
    {
        $body = $this->templating->render('AppBundle:Mail:high_prospective.html.twig', array(
            'prospect' => $prospect,
            ));

        return $this->sendMessage($this->expediteur, $this->admins, 'Waou '.$prospect->getMarket().' '.$prospect->getGainPercent().'% !!!', $body);
    }

    public function sendTopMarketReport($begin, $end, $results)
    {
        $body = $this->templating->render('AppBundle:Mail:top_markets.html.twig', array(
            'begin' => $begin,
            'end' => $end,
            'results' => $results,
            ));

        return $this->sendMessage($this->expediteur, $this->admins, 'Rapport de marché', $body);
    }

    public function sendLimitReached($limit, $data)
    {
        $body = $this->templating->render('AppBundle:Mail:limit_reached.html.twig', array(
            'limit' => $limit,
            'data' => $data,
            ));

        $title = $limit->getName();
        $title .= ($limit->getEqual()=='greater')? ' above ' : ' under ';
        $title .= $limit->getValue();
        $title .= ' !!';

        return $this->sendMessage($this->expediteur, $this->admins, $title, $body);
    }

    public function sendMcAfeeAlert($tweet)
    {
        $body = $this->templating->render('AppBundle:Mail:mcAfee.html.twig', array(
            'tweet' => $tweet,
            ));

        return $this->sendMessage($this->expediteur, $this->admins, "New mcAfee alert !", $body);
    }

    protected function sendMessage($from, $to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();

        $mail
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/html');

        if ($this->mailer->send($mail, $failures)) {
            return true;
        } else {
            return $failures;
        }
    }

    public function registerPlugin(\Swift_Events_EventListener $plugin)
    {
        $this->mailer->registerPlugin($plugin);
    }
}
