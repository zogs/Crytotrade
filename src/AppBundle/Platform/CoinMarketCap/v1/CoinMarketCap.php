<?php

namespace AppBundle\Platform\CoinMarketCap\v1;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;

class CoinMarketCap {

  private $version = '1';
  //private $apikey = '';
  //private $apisecret = '';

  public function __construct()
  {

  }

  public function getMarket($name, $fiat = 'USD')
  {
    $name = $this->formatMarketName($name);
    $name = str_replace('-(abc)', '', $name);
    $uri = 'https://api.coinmarketcap.com/v1/ticker/'.$name.'/?convert='.$fiat;
    $result = $this->apiCall($uri);

    return $result[0];
  }

  public function getMarkets($limit = 10, $fiat = 'USD')
  {
    $uri = "https://api.coinmarketcap.com/v1/ticker/?limit=".$limit."&convert=".$fiat;
    $result = $this->apiCall($uri);

    return $result;
  }

  public function getFiatPrice($currency, $fiat = 'USD')
  {
    $uri = "https://api.coinmarketcap.com/v1/ticker/".$currency."/?convert=EUR";
    $result = $this->apiCall($uri);

    return $result[0]['price_'.strtolower($fiat)];
  }

  public function getBitcoinPrice($fiat = 'USD')
  {
    return $this->getFiatPrice('bitcoin', $fiat);
  }

  private function apiCall($uri) {
        $nonce=time();
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $execResult = curl_exec($ch);
        $query = json_decode($execResult, true);
        if(isset($query['error'])) throw new \Exception("La requête CoinMarketCap n'a pu aboutir... [".$uri."]");
        return $query;
  }

  private function formatMarketName($name)
  {
    $formatted = str_replace(' ', '-', strtolower($name));
    return $formatted;
  }
}