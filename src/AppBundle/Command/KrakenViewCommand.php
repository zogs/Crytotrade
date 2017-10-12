<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;

class KrakenViewCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
        ->setName('market:kraken:view')
        ->setDescription('')
        //->addArgument('action', InputArgument::REQUIRED, 'Action to perform ? [add|view|remove|remove-all|list|add-defaults]')
        //->addOption('name', null, InputOption::VALUE_REQUIRED, 'Nom de la ville')
        //->addOption('lat', null, InputOption::VALUE_REQUIRED, 'Lattitude de la ville')
        //->addOption('lon', null, InputOption::VALUE_REQUIRED, 'Longitude de la ville')
        //->addOption('id', null, InputOption::VALUE_REQUIRED, 'Id du point')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $kraken = $this->getContainer()->get('kraken_api');    

        $assets = $kraken->getBalances();
        $assets = $assets['result'];

        $table = new Table($output);
        $table->setHeaders(array('Currency', 'Balance'));
        foreach ($assets as $market => $amount) {
          $table->addRows(array(
                array(
                    $market,
                    $amount                     
                ),
            ));      
        }
        $table->setStyle('compact');
        $table->render();
        
    }

}