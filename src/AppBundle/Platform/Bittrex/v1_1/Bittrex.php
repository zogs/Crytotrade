<?php

namespace AppBundle\Platform\Bittrex\v1_1;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


use AppBundle\Entity\Coin;
use AppBundle\Manager\CoinManager;
use AppBundle\Platform\CoinMarketCap\v1\CoinMarketCap;

class Bittrex {

  private $version = '1.1';
  private $apikey;
  private $apisecret;
  
  public function __construct(CoinMarketCap $coinmarketcap, CoinManager $coins, $apikey, $apisecret)
  {
    $this->apikey = $apikey;
    $this->apisecret = $apisecret;
    $this->coin_manager = $coins;
    $this->coinmarketcap = $coinmarketcap;

    $this->baseFiat = 'EUR';
    $this->bitcoinFiatPrice = $coinmarketcap->getFiatPrice('bitcoin',$this->baseFiat);

    $this->availableMarkets = $this->getApiMarkets();

    $this->marketNameAdapter = array(
      'PowerLedger' => 'Power Ledger',
      //... add here
    );

  }

  public function getBalances()
  {
    $currencies = $this->getApiBalances();
    
    foreach ($currencies as $key => $curr) {

      //bittrex
      $amount = $curr['Balance'];
      $name = $curr['Currency'];
      $market = $this->findAvailableMarketName($name);

      //skip null amount
      if($amount==0) continue;

      //coinmarketcap
      $market = $this->coinmarketcap->getMarket($market, $this->baseFiat);
      
      //build coin
      $coin = $this->coin_manager->buildCoin($market, $amount, 'bittrex');
      
      //add coin
      $res[] = $coin;
    }

    return $res;
  }

  public function getMarkets()
  {
    $markets = $this->getApiMarkets();

    foreach ($markets as $key => $market) {
      $res[$market['MarketCurrency']] = array(
        'name' => $market['MarketCurrency'],
        'base' => $market['BaseCurrency'],
        'fullname' => $market['MarketCurrencyLong'],
        'baseFullname' => $market['BaseCurrencyLong'],
        'minTradeSize' => $market['MinTradeSize'],
        'market' => $market['MarketName'],
        'isActive' => $market['IsActive'],
        'created' => $market['Created']
      );
    }

    return $res;
  }

  public function getTicker($market, $bidask = null)
  {
    return $this->getApiTicker($market, $bidask);
  }

  // convert traditional ['BTC','ETH'] to platform dedicated name
  public function getMarketCode($market)
  {
    return $market[0].'-'.$market[1];
  }

  public function getFiatEquivalence($currency)
  {
    return $this->bitcoinFiatPrice * $this->getBitcoinRate($currency, 'Ask');
  }

  public function getBitcoinRate($currency)
  {
    return $this->getApiTicker('BTC-'.$currency, 'Ask');
  }

  public function getApiTicker($market, $bidask = null)
  {
    $uri = "https://bittrex.com/api/v1.1/public/getticker?market=".urlencode($market);
    $query = $this->apiCall($uri);
    $result = $query['result'];

    return isset($bidask)? $result[$bidask] : $result;
  }

  public function getApiBalances()
  {
    $uri='https://bittrex.com/api/v1.1/account/getbalances';
    $query = $this->apiCall($uri);
    return $query['result'];    
  }

  public function getApiMarkets()
  {
    $uri = "https://bittrex.com/api/v1.1/public/getmarkets";
    $query = $this->apiCall($uri);
    return $query['result'];
  }

  private function truncAddress($add)
  {
    return !empty($add)? substr($add, 0, 6).'...'.substr($add, -3) : '-';
  }

  private function apiCall($uri) {
        $nonce=time();
        if(strpos($uri, '?') === false) $uri .= '?';
        $uri.= '&apikey='.$this->apikey.'&nonce='.$nonce;
        $sign=hash_hmac('sha512',$uri,$this->apisecret);
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:'.$sign));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $execResult = curl_exec($ch);
        $query = json_decode($execResult, true);
        if($query['success'] === false || !isset($query['success'])) throw new \Exception("La requête n'a pu aboutir... [$uri]");
        return $query;
  }

  private function findAvailableMarket($symbol) 
  {
    foreach ($this->availableMarkets as $market) {
      if($market['MarketCurrency'] == $symbol) return $market;
    }
    return false;
  }

  private function findAvailableMarketName($symbol)
  {
    $market = $this->findAvailableMarket($symbol);
    $name = $market['MarketCurrencyLong'];

    // search for name adaptation and if needed return it
    foreach ($this->marketNameAdapter as $key => $value) {
      if($key == $name) return $value;
    }

    //return default name
    return $name;
  }
}