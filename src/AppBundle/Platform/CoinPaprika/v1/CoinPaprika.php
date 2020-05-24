<?php

namespace AppBundle\Platform\CoinPaprika\v1;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;

class CoinPaprika {

  private $version = '1';
  //private $apikey = '';
  //private $apisecret = '';

  public function __construct()
  {

  }

  private function nameAdapter($name) {
    $name = strtolower($name);
    if($name == 'bitcoin') return 'btc-bitcoin';
    if($name == 'komodo') return 'kmd-komodo';
    if($name == 'wax') return 'wax-wax';
    if($name == 'flixxo') return 'flixx-flixxo';
    if($name == 'ethereum') return 'eth-ethereum';
    if($name == 'adshares') return 'ads-adshares';
    if($name == 'hoqu') return 'hqx-hoqu';
    if($name == 'pirate-chain') return 'arrr-pirate';
    return $name;
  }

  public function getMarket($coin, $fiat = 'USD')
  {
    $name = $this->nameAdapter($coin);
    $uri = 'https://api.coinpaprika.com/v1/tickers/'.$name.'/?quotes='.$fiat;
    $result = $this->apiCall($uri);

    return $result[0];
  }

  public function getFiatPrice($coin, $fiat = 'USD')
  {
    $coin = $this->nameAdapter($coin);
    $uri = "https://api.coinpaprika.com/v1/tickers/".$coin."/?quotes=".$fiat;
    $result = $this->apiCall($uri);

    return $result['quotes']['price'];
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
        if(isset($query['error'])) throw new \Exception("La requête CoinPaprika n'a pu aboutir... [".$uri."]");
        return $query;
  }


}