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

class LimitCheckCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
        ->setName('limit:check')
        ->setDescription('Check high or low limit reached by a token')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getContainer();
        $this->output = $output;

        $coinmarket = $container->get('coinmarketcap_v1');

        $this->output->writeln("<info>Finding all limits...</info>");
        $all = $container->get('doctrine.orm.entity_manager')->getRepository('AppBundle:LimitCheck')->findAll();
        $this->output->writeln("<comment>".count($all)." found !</comment>");

        try {
            foreach ($all as $limit) {

                $this->output->writeln("<info>Checking ".$limit->getName()." limit...</info>");
               
               $data = $coinmarket->getMarket($limit->getName());
               $data = $data[0];
               $param = $limit->getParam();

               if(isset($data[$param])) {

                    $value = $data[$param];

                    if('greater' == $limit->getEqual()) {

                        if($value >= $limit->getValue()) $this->limitReached($limit, $data);
                        else $output->writeln('<comment>Done.</comment>');

                    }
                    elseif('lower' == $limit->getEqual()) {

                        if($value <= $limit->getValue()) $this->limitReached($limit, $data);
                        else $output->writeln('<comment>Done.</comment>');

                    }
                    elseif('equal' == $limit->getEqual()) {

                        if($value == $limit->getValue()) $this->limitReached($limit, $data);
                        else $output->writeln('<comment>Done.</comment>');

                    }
                    else {
                        throw new \Exception("Equal param (".$limit->getEqual().") is not valid" , 1);
                    }
               }
               else {
                throw new \Exception("Param (".$limit->getParam().") not found on coinmarketcap data...", 1);            
               }
           }
        }
        catch(\Exception $e) {
            $this->output->writeln('<error>'.$e->getMessage().' </error>');
            dump($limit);
            dump($data);       
        }
        
    }

    private function limitReached(LimitCheck $limit, $data)
    {

        $this->output->writeln("<comment>".strtoupper($limit->getName())." limit reached !</comment>");
        $this->output->writeln("<info>Sending email...</info>");

        $mailer = $this->getContainer()->get('app.mailer');
        $mailer->sendLimitReached($limit, $data);
        
        $this->output->writeln("<info>Done.</info>");
    }

}