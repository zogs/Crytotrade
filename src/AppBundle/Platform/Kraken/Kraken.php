<?php

namespace AppBundle\Platform\Kraken;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


use AppBundle\Platform\CoinMarketCap\v1\CoinMarketCap;
use AppBundle\Platform\Kraken\KrakenAPIClient;

class Kraken {

  private $version = '';
  private $apikey;
  private $apisecret;
  
  public function __construct(CoinMarketCap $coinMarketCap, $apikey, $apisecret)
  {
    $this->apikey = $apikey;
    $this->apisecret = $apisecret;

    $this->baseFiatCurrency = 'EUR';
    $this->bitcoinFiatPrice = $coinMarketCap->getFiatPrice('bitcoin',$this->baseFiatCurrency);

    //instanciate kraken client
    $beta = false; 
    $url = $beta ? 'https://api.beta.kraken.com' : 'https://api.kraken.com';
    $sslverify = $beta ? false : true;
    $version = 0;
    $this->api = new KrakenAPI($this->apikey, $this->apisecret, $url, $version, $sslverify);

  }

  public function getTicker($market)
  {
    $res = $this->getApiTicker($market);
    $res = $res['result'];
    $res = $res[key($res)];
    return array(
      'Bid' => (float) $res['b'][0],
      'Ask' => (float) $res['a'][0],
      'Last' => (float) $res['c'][0]
    );
  }

  // convert traditional ['BTC','ETH'] to platform dedicated name
  public function getMarketCode($market)
  {
    $market = array_map(function($n) { return str_replace(array('BTC'),array('XBT'),$n); }, $market);
    return $market[1].$market[0];
  }

  // Query a public list of active assets and their properties: 
  public function getApiMarkets() 
  {
    return $this->api->QueryPublic('Assets');
  }

  // Query public ticker info for BTC/USD pair:
  public function getApiTicker($market)
  {
    return $this->api->QueryPublic('Ticker', array('pair' => $market));
  }


 //Query public recent trades for BTC/EUR pair since 2013-08-07T18:20:42+00:00. 
  public function getApiTrades($market)
  {
    return $this->api->QueryPublic('Trades', array('pair' => $market));
    
  }

  //Query private asset balance
  public function getBalances()
  {
    return $this->api->QueryPrivate('Balance');
  }

  // Query private open orders and included related trades
  public function getOrders()
  {
    return $this->api->QueryPrivate('OpenOrders', array('trades' => true));    
  }

  /**
   * Add a standard order: sell 1.123 BTC/USD @ limit $120
   * @param string $market 'BTCUSD'
   * @param string $type   buy|sell
   * @param number $price  price
   * @param number $volume volume
   * @param string $limit  limit
   */
  public function sell($market, $price, $volume, $limit)
  {
    return $this->api->QueryPrivate('AddOrder', array(
    'pair' => $market, 
    'type' => 'sell', 
    'ordertype' => $limit, 
    'price' => $price, 
    'volume' => $volume
    ));
  }

  /**
   * Add a standard order: buy €300 worth of BTC at market at 2013-08-12T09:27:22+0000 
   * @param  string $market 'BTCUSD'
   * @param  number $price  price
   * @param  number $volume volume
   * @param  timestamp $timestamp time at which order is pass
   */
  public function buy($market, $volume, $timestamp = null)
  {
    return $this->api->QueryPrivate('AddOrder', array(
        'pair' => $market, 
        'type' => 'buy', 
        'ordertype' => 'market', 
        'oflags' => 'viqc',
        'volume' => $volume, 
        'starttm' => $timestamp
    ));

  }


}