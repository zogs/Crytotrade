<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use AppBundle\Platform\CoinPaprika\v1\CoinPaprika;
use AppBundle\Entity\Coin;

class WalletDepositManager
{
  public function __construct(EntityManager $em, CoinPaprika $api, CoinManager $coins) {

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

      $name = $deposit->getFullname();
      $amount = $deposit->getAmount();
      $wallet = $deposit->getWallet();

      try {
        $to_btc = $this->api->getMarket($name, 'BTC');
        $to_eur = $this->api->getMarket($name, $this->baseFiat);
      } catch(\Exception $e) {
        throw $e;
      }

      $coin = new Coin();
      $coin->setName($to_eur['symbol']);
      $coin->setFullname($to_eur['name']);
      $coin->setAmount($amount);
      $coin->setAmountEur($amount * $to_eur['quotes']['price']);
      $coin->setLocation($wallet);
      $coin->setPriceBtc($to_btc['quotes']['price']);
      $coin->setPriceEur($to_eur['quotes']['price']);
      $coin->setVolumeEur24h($to_eur['quotes']['volume_24h']);
      $coin->setPercentChange1h($to_eur['quotes']['percent_change_1h']);
      $coin->setPercentChange24h($to_eur['quotes']['percent_change_24h']);
      $coin->setPercentChange7d($to_eur['quotes']['percent_change_7d']);

      $coins[] = $coin;

      sleep(1);
    }

    return $coins;

  }

}

