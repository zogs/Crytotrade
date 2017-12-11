<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;

class BittrexViewCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
        ->setName('bittrex:balance')
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

        $bittrex = $this->getContainer()->get('bittrex_v1.1');
        
        $currencies = $bittrex->getCurrencies();
        $table = new Table($output);
        $table->setHeaders(array('Currency', 'Fiat', 'Balance', 'Available', 'Pending', 'Address'));
        $total = 0;
        foreach ($currencies as $res) {
          $table->addRows(array(
                array(
                    $res['name'],
                    $res['total'],
                    $res['balance'],  
                    $res['available'],
                    $res['pending'],
                    $res['short_address'],                      
                ),
            ));  
            $total += $res['total'];

        }
        $table->addRow(new TableSeparator());
        $table->addRow(array('TOTAL', $total.' €', '', '', '', ''));

        
        $table->render();
        

    }

}