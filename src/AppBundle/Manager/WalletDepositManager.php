<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use AppBundle\Platform\CoinMarketCap\v1\CoinMarketCap;
use AppBundle\Entity\Coin;

class WalletDepositManager 
{
  public function __construct(EntityManager $em, CoinMarketCap $api, CoinManager $coins) {

    $this->em = $em;
    $this->api = $api;
    $this->coins = $coins;

    $this->baseFiat = 'EUR';
    $this->bitcoinPrice = $api->getFiatPrice('bitcoin',$this->baseFiat);

  }

  public function getAll()
  {
    $deposits = $this->em->getRepository('AppBundle:WalletDeposit')->findAll();

    $coins = [];
    foreach ($deposits as $deposit) {
      
      $market = $this->api->getMarket(
        $deposit->getFullname(),
        $this->baseFiat
      );

      $coin = $this->coins->buildCoin(
        $market, 
        $deposit->getAmount(), 
        $deposit->getWallet()
      );

      $coins[] = $coin;
    }

    return $coins;

  }

}

