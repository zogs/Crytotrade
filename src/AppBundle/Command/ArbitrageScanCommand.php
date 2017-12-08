<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;

use AppBundle\Entity\ArbitrageProspective;

class ArbitrageScanCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
        ->setName('arbitrage:scan')
        ->setDescription('')
        //->addArgument('action', InputArgument::REQUIRED, 'Action to perform ? [add|view|remove|remove-all|list|add-defaults]')
        ->addOption('top', null, InputOption::VALUE_REQUIRED, 'nb top market to scan')
        ->addOption('fiat', null, InputOption::VALUE_REQUIRED, 'fiat base')
        ->addOption('mail', null, InputOption::VALUE_NONE, 'send warning email')
        //->addOption('id', null, InputOption::VALUE_REQUIRED, 'Id du point')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getContainer();

        $bittrex = $container->get('bittrex_v1.1');
        $kraken = $container->get('kraken_api');
        $coinmarket = $container->get('coinmarketcap_v1');

        $nb = 30;
        if($input->getOption('top') != null) {
            $nb = $input->getOption('top');
        }

        $fiatbase = 'EUR';
        if($input->getOption('fiat') != null) {
            $fiatbase = $input->getOption('fiat');
        }

        $top_markets = $coinmarket->getMarkets($nb,$fiatbase);

        $output->writeln("<info>Plateforme d'échange disponible :</info>");
        $output->writeln("<comment>1 - Bittrex</comment>");
        $output->writeln("<comment>2 - Kraken</comment>");

        // find market price
        $output->writeln("<info>Check ".$nb." top markets...</info>");
        $markets = [];
        foreach ($top_markets as $k => $market) {
            
            $symbol = $market['symbol'];
            $market = array('BTC',$symbol);
            $name = 'BTC-'.$symbol;

            if($symbol == 'BTC') continue;

            $output->write('<info>Check '.$name.'... </info>');

            // check bittrex
            try {
                $code = $bittrex->getMarketCode($market);                
                $markets[$name]['bittrex'] =  $bittrex->getTicker($code);
                $output->write('<comment>Bittrex OK. </comment>');
            }
            catch(\Exception $e) {
                $markets[$name]['bittrex'] = 'No market';
            }

            // check kraken
            try {
                $code = $kraken->getMarketCode($market);                
                $markets[$name]['kraken'] =  $kraken->getTicker($code);
                $output->write('<comment>Kraken OK. </comment>');
            }
            catch(\Exception $e) {
                $markets[$name]['kraken'] = 'No market';
            }

            $output->writeln('');
        }

        // find market high and low
        $diff = [];
        foreach ($markets as $market => $platforms) {
            
            $maxbid = 0;
            $bidplatform = null;
            $minask = 100000000;
            $askplatform = null;

            foreach ($platforms as $platform => $ticker) {
                if($ticker == 'No market') continue;
                if($ticker['Bid'] > $maxbid) {
                    $maxbid = $ticker['Bid'];
                    $bidplatform = $platform;  
                } 
                if($ticker['Ask'] < $minask) {
                    $minask = $ticker['Ask'];
                    $askplatform = $platform;
                }
            }

            $diff[$market] = array(
                'maxbid' => $maxbid,
                'bidplatform' => $bidplatform,
                'minask' => $minask,
                'askplatform' => $askplatform,
                'diff' => ($maxbid - $minask)
            );
        }

        //find opportunities
        $opportunities = [];
        foreach ($diff as $market => $offers) {
            
            // prix d'achat - prix de vente
            $diff = $offers['maxbid'] - $offers['minask'];
            // si c'est positif il y a une opportunité
            if($diff > 0) {
                $opportunities[$market] = $offers;
            }
        }

        //calcul gain
        $btcprice = $coinmarket->getBitcoinPrice('EUR');
        $gains = [];
        foreach ($opportunities as $market => $offers) {
            
            //en euro
            $achat = $offers['minask'] * $btcprice;
            $vente = $offers['maxbid'] * $btcprice;
            $gain = $vente - $achat;

            $gains[$market] = array(
                'prix achat' => $achat,
                'prix vente' => $vente,
                'diff' => $gain, 
                'gain pour 1000' => (1000 / $achat * $vente) - 1000,
                'gain pour 5000' => (5000 / $achat * $vente) - 5000,
                'gain pour 10000' => (10000 / $achat * $vente) - 10000,
                '% gain' => round(((1000 / $achat * $vente) - 1000) / 1000 * 100,2),
                'askplatform' => $offers['askplatform'],
                'bidplatform' => $offers['bidplatform'],
            );        
        }

        //save in database
        $em = $container->get('doctrine.orm.entity_manager');
        $prospectives = array();
        foreach ($gains as $market => $d) {
            
            $prospect = new ArbitrageProspective();
            $prospect->setMarket($market);
            $prospect->setBuyPrice($d['prix achat']);
            $prospect->setBuyPlatform($d['askplatform']);
            $prospect->setSellPrice($d['prix vente']);
            $prospect->setSellPlatform($d['bidplatform']);
            $prospect->setBitcoinPrice($btcprice);
            $prospect->setFiatBase($fiatbase);
            $prospect->setPriceDiff($d['diff']);
            $prospect->setGainPercent($d['% gain']);
            $prospect->setGainPer1000($d['gain pour 1000']);
            $prospect->setGainPer5000($d['gain pour 5000']);
            $prospect->setGainPer10000($d['gain pour 10000']);

            $em->persist($prospect);
            $prospectives[] = $prospect;
        }
        $em->flush();



        //print result
        $output->writeln("<info>Conseil d'arbitrage</info>");
        if(count($prospectives) > 0) {
            foreach ($prospectives as $p) {
                
                $output->writeln('<comment>Acheter du '.$p->getMarket().' sur '.$p->getBuyPlatform().' à '.round($p->getBuyPrice(),3).'€ et le vendre sur '.$p->getSellPlatform().' à '.round($p->getSellPrice(),3).'€</comment>');
                $output->writeln($p->getGainPercent().'% de bénéfice probable');
                $output->writeln('Gain pour 1 action: '.$p->getPriceDiff());
                $output->writeln('Gain pour 1000'.$fiatbase.': '.$p->getGainPer1000());
                $output->writeln('Gain pour 5000'.$fiatbase.': '.$p->getGainPer5000());
                $output->writeln('Gain pour 10000'.$fiatbase.': '.$p->getGainPer10000());

                $output->writeln('');

                $mailer = $container->get('app.mailer');
                if($p->getGainPercent() >= 5) {
                    if($input->getOption('mail') != null) {
                        $mailer->sendHighArbitrageProspective($p);                        
                    }
                }
            }                
        }
        else {
            $output->writeln("<error>Pas d'opportunites d'arbitrage pour le momenet");
        }
        
    }

}