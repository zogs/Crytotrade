<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Coin;

class CoinManager 
{

  public function buildCoin($data, $amount = 0, $wallet = '')
  {
      $coin = new Coin();
      $coin->setName($data['symbol']);
      $coin->setFullname($data['name']);
      $coin->setAmount($amount);
      $coin->setAmountEur($amount * $data['price_eur']);
      $coin->setLocation($wallet);
      $coin->setPriceBtc($data['price_btc']);
      $coin->setPriceEur($data['price_eur']);
      $coin->setPriceUsd($data['price_usd']);
      $coin->setVolumeUsd24h($data['24h_volume_usd']);
      $coin->setVolumeEur24h($data['24h_volume_eur']);
      $coin->setPercentChange1h($data['percent_change_1h']);
      $coin->setPercentChange24h($data['percent_change_24h']);
      $coin->setPercentChange7d($data['percent_change_7d']);

    return $coin;

  }

}

