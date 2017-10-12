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

  public function getMarkets($limit = 10, $fiat = 'USD')
  {
    $uri = "https://api.coinmarketcap.com/v1/ticker/?limit=".$limit."&convert=".$fiat;
    $query = $this->apiCall($uri);

    return $query;
  }

  public function getFiatPrice($currency, $fiat = 'USD')
  {
    $uri = "https://api.coinmarketcap.com/v1/ticker/".$currency."/?convert=EUR";
    $query = $this->apiCall($uri);

    return $query[0]['price_'.strtolower($fiat)];
  }

  public function getBitcoinPrice($fiat = 'USD')
  {
    return $this->getFiatPrice('bitcoin', $fiat);
  }

  private function truncAddress($add)
  {
    return !empty($add)? substr($add, 0, 6).'...'.substr($add, -3) : '-';
  }

  private function apiCall($uri) {
        $nonce=time();
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $execResult = curl_exec($ch);
        $query = json_decode($execResult, true);
        if(isset($query['error'])) return $this->output->writeln("<error>La requête n'a pu aboutir... [".$uri."]</error>");
        return $query;
  }
}