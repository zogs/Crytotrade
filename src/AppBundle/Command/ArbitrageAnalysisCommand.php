<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;

class ArbitrageAnalysisCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
        ->setName('market:arbitrage:recap')
        ->setDescription('')
        //->addArgument('action', InputArgument::REQUIRED, 'Action to perform ? [add|view|remove|remove-all|list|add-defaults]')
        ->addOption('days', null, InputOption::VALUE_REQUIRED, 'from nb days')
        ->addOption('mail', null, InputOption::VALUE_NONE, 'mail')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $begin = new \Datetime();
        $begin->modify('-30 days');
        $end = new \Datetime('now');
               
        if($input->getOption('days') != null) {

            $from = $input->getOption('days');
            $begin = new \Datetime('now');
            $begin->modify('-'.$from.' days');
        }


        $results = $em->getRepository('AppBundle:ArbitrageProspective')->getBestMarket($begin,$end);

        $output->writeln('<comment>Entre le '.$begin->format('d-m-Y').' et le '.$end->format('d-m-Y').'</comment>');
        $output->writeln("<info>Les marchés les plus rentables sont:");
        foreach ($results as $k => $market) {
            $output->writeln('<info>'.$k." - ".$market['market']." (gain de ".round($market['sum'],2)." pour 1000)</info>");
        }


        if($input->getOption('mail') != null) {

            $mailer = $this->getContainer()->get('app.mailer');
            $mailer->sendTopMarketReport($begin, $end, $results);
        }

    }

}