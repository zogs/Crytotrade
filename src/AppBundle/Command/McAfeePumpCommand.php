<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;

use AppBundle\Entity\LimitCheck;

class McAfeePumpCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
        ->setName('mcafee:pump')
        ->setDescription('Check for mcAfee last tweet and send me an email')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getContainer();
        $this->output = $output;

        $twitterName = 'officialmcafee';
        $output->writeln("<info>Check for mcAfee's tweets...");


        $settings = array(
            'oauth_access_token' => $this->getContainer()->getParameter('twitter.oauth_access_token'),
            'oauth_access_token_secret' => $this->getContainer()->getParameter('twitter.oauth_access_token_secret'),
            'consumer_key' => $this->getContainer()->getParameter('twitter.consumer_key'),
            'consumer_secret' => $this->getContainer()->getParameter('twitter.consumer_secret'),
        );

        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name='.$twitterName.'&count=1';
        $requestMethod = 'GET';
        
        $twitter = new \TwitterAPIExchange($settings);
        $json = $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        //get last tweet
        $tweets = json_decode($json);
        $tweet = $tweets[0];
        
        //compare with saved tweet
        $saved = (file_exists("var/logs/mcafee_id.txt") === true)? file_get_contents("var/logs/mcafee_id.txt") : null;

        if($saved != $tweet->id) {

            $output->writeln('<comment>New tweet detected... Sending email...</comment>');

            $this->getContainer()->get('app.mailer')->sendMcAfeeAlert($tweet);
            
            //save last tweet id
            $content = $tweet->id;
            $fp = fopen("var/logs/mcafee_id.txt","wb");
            fwrite($fp,$content);
            fclose($fp);
        } else {

            $output->writeln('<comment>No new tweet...');
        }


    }



}