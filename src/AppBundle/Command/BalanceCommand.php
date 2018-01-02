<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;

class BalanceCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
        ->setName('balance')
        ->setDescription('Show balances')
        ->addArgument('platform', InputArgument::OPTIONAL, '', 'all')
        //->addOption('id', null, InputOption::VALUE_REQUIRED, 'Id du point')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $walletManager = $container->get('wallet_manager');
        $bittrex = $this->getContainer()->get('bittrex_v1.1');
        $kraken = $this->getContainer()->get('kraken_api');   

        // get coins
        $param = $input->getArgument('platform');
        if($param == 'all' || $param == 'bittrex') $platforms['bittrex'] = $bittrex->getBalances();
        if($param == 'all' || $param == 'kraken') $platforms['kraken'] = $kraken->getBalances();
        if($param == 'all' || $param == 'wallet') $platforms['wallet'] = $walletManager->getAll();

        $table = new Table($output);
        $table->setHeaders(array('Coin', 'Amount', 'Balance', 'Location', '24h change', '7d change', 'Coin'));
        $total = 0;

        foreach ($platforms as $platform => $coins) {
            
            foreach ($coins as $coin) {
                
                $table->addRows(array(
                    array(
                        $coin->getName(),
                        $coin->getAmountEur().' €',
                        $coin->getAmount(),
                        $coin->getLocation(),
                        ($coin->getPercentChange24h() > 0 ? '<info>+'.$coin->getPercentChange24h().'</info>' : '<comment>'.$coin->getPercentChange24h().'</comment>'),
                        ($coin->getPercentChange7d() > 0 ? '<info>+'.$coin->getPercentChange7d().'</info>' : '<comment>'.$coin->getPercentChange7d().'</comment>'),
                        $coin->getName()
                    )
                ));

                $total += $coin->getAmountEur();
            }
        }

        $table->addRow(new TableSeparator());
        $table->addRow(array('TOTAL', $total.' €', '', '', '', ''));
      
        $table->render();
        

    }

}