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
        $errors = array();

        $walletManager = $container->get('wallet_manager');
        $bittrex = $this->getContainer()->get('bittrex_v1.1');
        $kraken = $this->getContainer()->get('kraken_api');   

        // get wanted platform
        $param = $input->getArgument('platform');
        // get coins via API
        if($param == 'all' || $param == 'bittrex') {
            try{
               $platforms['bittrex'] = $bittrex->getBalances();
            } catch(\Exception $e){
                $errors[] = "Bittrex API not reachable... (".$e->getMessage().")";
            }
        }
        if($param == 'all' || $param == 'kraken') {
            try{
                $platforms['kraken'] = $kraken->getBalances();
            } catch(\Exception $e){
                $errors[] = "Kraken API not reachable... (".$e->getMessage().")";
            }
        }
        if($param == 'all' || $param == 'wallet') {
            try{
                $platforms['wallet'] = $walletManager->getAll();
            } catch(\Exception $e){
                $errors[] = "Wallet API not reachable... (".$e->getMessage().")";
            }
        }

        $table = new Table($output);
        $table->setHeaders(array('Coin', 'Amount', 'Balance', 'Location', '24h', '7d', 'Coin'));
        $total = 0;

        // aggregate coins
        foreach ($platforms as $platform => $_coins) {
            foreach ($_coins as $coin) {
                $coins[] = $coin;
            }
        }    

        // order coins
        usort($coins, function ($a, $b) {
            return $b->getAmountEur() - $a->getAmountEur();
        });

        // add coins to table
        foreach ($coins as $coin) {
                
            $table->addRows(array(
                array(
                    $coin->getName(),
                    round($coin->getAmountEur()).' €',
                    round($coin->getAmount(),4),
                    ucfirst($coin->getLocation()),
                    ($coin->getPercentChange24h() > 0 ? '<info>+'.$coin->getPercentChange24h().'</info>' : '<comment>'.$coin->getPercentChange24h().'</comment>'),
                    ($coin->getPercentChange7d() > 0 ? '<info>+'.$coin->getPercentChange7d().'</info>' : '<comment>'.$coin->getPercentChange7d().'</comment>'),
                    $coin->getName()
                )
            ));

            $total += $coin->getAmountEur();
        }

        // display table
        $table->addRow(new TableSeparator());
        $table->addRow(array('TOTAL', round($total).' €', '', '', '', ''));      
        $table->render();
        
        // display errors
        foreach ($errors as $error) {
            $output->writeln("<comment>$error</comment>");
        }

    }

}