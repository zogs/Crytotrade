<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArbitrageProspective.
 *
 * @ORM\Table(name="arbitrage_prospective",indexes={@ORM\Index(name="arbitrage_prospective_date_idx", columns={"datetime"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ArbitrageProspectiveRepository")
 */
class ArbitrageProspective
{
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $datetime;

    /**
     * @ORM\Column(name="gain_percent", type="float", length=128)
     */
    private $gainPercent;

    /**
     * @ORM\Column(name="gain_1000", type="float", length=128)
     */
    private $gainPer1000;

    /**
     * @ORM\Column(name="gain_5000", type="float", length=128)
     */
    private $gainPer5000;

    /**
     * @ORM\Column(name="gain_10000", type="float", length=128)
     */
    private $gainPer10000;

    /**
     * @ORM\Column(name="buy_price", type="float", length=128)
     */
    private $buyPrice;

    /**
     * @ORM\Column(name="buy_platform", type="string")
     */
    private $buyPlatform;

    /**
     * @ORM\Column(name="sell_price", type="float", length=128)
     */
    private $sellPrice;

    /**
     * @ORM\Column(name="sell_platform", type="string")
     */
    private $sellPlatform;

    /**
     * @ORM\Column(name="price_diff", type="float", length=128)
     */
    private $priceDiff;

    /**
     * @ORM\Column(name="bitcoinPrice", type="float", length=128)
     */
    private $bitcoinPrice;

    /**
     * @ORM\Column(name="fiatbase", type="string")
     */
    private $fiatbase = 'USD';

    /**
     * @ORM\Column(name="market", type="string")
     */
    private $market;


    public function __construct()
    {
        $this->datetime = new \Datetime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return ArbitrageProspective
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set gainPercent
     *
     * @param float $gainPercent
     *
     * @return ArbitrageProspective
     */
    public function setGainPercent($gainPercent)
    {
        $this->gainPercent = $gainPercent;

        return $this;
    }

    /**
     * Get gainPercent
     *
     * @return float
     */
    public function getGainPercent()
    {
        return $this->gainPercent;
    }

    /**
     * Set gainPer1000
     *
     * @param float $gainPer1000
     *
     * @return ArbitrageProspective
     */
    public function setGainPer1000($gainPer1000)
    {
        $this->gainPer1000 = $gainPer1000;

        return $this;
    }

    /**
     * Get gainPer1000
     *
     * @return float
     */
    public function getGainPer1000()
    {
        return $this->gainPer1000;
    }

    /**
     * Set gainPer5000
     *
     * @param float $gainPer5000
     *
     * @return ArbitrageProspective
     */
    public function setGainPer5000($gainPer5000)
    {
        $this->gainPer5000 = $gainPer5000;

        return $this;
    }

    /**
     * Get gainPer5000
     *
     * @return float
     */
    public function getGainPer5000()
    {
        return $this->gainPer5000;
    }

    /**
     * Set gainPer10000
     *
     * @param float $gainPer10000
     *
     * @return ArbitrageProspective
     */
    public function setGainPer10000($gainPer10000)
    {
        $this->gainPer10000 = $gainPer10000;

        return $this;
    }

    /**
     * Get gainPer10000
     *
     * @return float
     */
    public function getGainPer10000()
    {
        return $this->gainPer10000;
    }

    /**
     * Set buyPrice
     *
     * @param float $buyPrice
     *
     * @return ArbitrageProspective
     */
    public function setBuyPrice($buyPrice)
    {
        $this->buyPrice = $buyPrice;

        return $this;
    }

    /**
     * Get buyPrice
     *
     * @return float
     */
    public function getBuyPrice()
    {
        return $this->buyPrice;
    }

    /**
     * Set buyPlatform
     *
     * @param string $buyPlatform
     *
     * @return ArbitrageProspective
     */
    public function setBuyPlatform($buyPlatform)
    {
        $this->buyPlatform = $buyPlatform;

        return $this;
    }

    /**
     * Get buyPlatform
     *
     * @return string
     */
    public function getBuyPlatform()
    {
        return $this->buyPlatform;
    }

    /**
     * Set sellPrice
     *
     * @param float $sellPrice
     *
     * @return ArbitrageProspective
     */
    public function setSellPrice($sellPrice)
    {
        $this->sellPrice = $sellPrice;

        return $this;
    }

    /**
     * Get sellPrice
     *
     * @return float
     */
    public function getSellPrice()
    {
        return $this->sellPrice;
    }

    /**
     * Set sellPlatform
     *
     * @param string $sellPlatform
     *
     * @return ArbitrageProspective
     */
    public function setSellPlatform($sellPlatform)
    {
        $this->sellPlatform = $sellPlatform;

        return $this;
    }

    /**
     * Get sellPlatform
     *
     * @return string
     */
    public function getSellPlatform()
    {
        return $this->sellPlatform;
    }

    /**
     * Set priceDiff
     *
     * @param float $priceDiff
     *
     * @return ArbitrageProspective
     */
    public function setPriceDiff($priceDiff)
    {
        $this->priceDiff = $priceDiff;

        return $this;
    }

    /**
     * Get priceDiff
     *
     * @return float
     */
    public function getPriceDiff()
    {
        return $this->priceDiff;
    }

    /**
     * Set bitcoinPrice
     *
     * @param float $bitcoinPrice
     *
     * @return ArbitrageProspective
     */
    public function setBitcoinPrice($bitcoinPrice)
    {
        $this->bitcoinPrice = $bitcoinPrice;

        return $this;
    }

    /**
     * Get bitcoinPrice
     *
     * @return float
     */
    public function getBitcoinPrice()
    {
        return $this->bitcoinPrice;
    }

    /**
     * Set fiatbase
     *
     * @param string $fiatbase
     *
     * @return ArbitrageProspective
     */
    public function setFiatbase($fiatbase)
    {
        $this->fiatbase = $fiatbase;

        return $this;
    }

    /**
     * Get fiatbase
     *
     * @return string
     */
    public function getFiatbase()
    {
        return $this->fiatbase;
    }

    /**
     * Set market
     *
     * @param string $market
     *
     * @return ArbitrageProspective
     */
    public function setMarket($market)
    {
        $this->market = $market;

        return $this;
    }

    /**
     * Get market
     *
     * @return string
     */
    public function getMarket()
    {
        return $this->market;
    }
}
