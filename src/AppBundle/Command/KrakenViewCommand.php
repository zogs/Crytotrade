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
        ->setName('kraken:balance')
        ->setDescription('')
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