<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coin.
 *
 */
class Coin
{
    private $name = '';
    private $fullname = '';
    private $amount = 0;
    private $amount_eur = 0;
    private $location = '';
    private $price_btc = 0;
    private $price_usd = 0;
    private $price_eur = 0;
    private $volume_usd_24h = 0;
    private $volume_eur_24h = 0;
    private $percent_change_1h = 0;
    private $percent_change_24h = 0;
    private $percent_change_7d = 0;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Coin
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return Coin
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Coin
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Coin
     */
    public function setAmountEur($amount)
    {
        $this->amount_eur = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmountEur()
    {
        return round($this->amount_eur,2);
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Coin
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set priceBtc
     *
     * @param string $priceBtc
     *
     * @return Coin
     */
    public function setPriceBtc($priceBtc)
    {
        $this->price_btc = $priceBtc;

        return $this;
    }

    /**
     * Get priceBtc
     *
     * @return string
     */
    public function getPriceBtc()
    {
        return $this->price_btc;
    }

    /**
     * Set priceUsd
     *
     * @param string $priceUsd
     *
     * @return Coin
     */
    public function setPriceUsd($priceUsd)
    {
        $this->price_usd = $priceUsd;

        return $this;
    }

    /**
     * Get priceUsd
     *
     * @return string
     */
    public function getPriceUsd()
    {
        return $this->price_usd;
    }

    /**
     * Set priceEur
     *
     * @param string $priceEur
     *
     * @return Coin
     */
    public function setPriceEur($priceEur)
    {
        $this->price_eur = $priceEur;

        return $this;
    }

    /**
     * Get priceEur
     *
     * @return string
     */
    public function getPriceEur()
    {
        return $this->price_eur;
    }

    /**
     * Set volumeUsd24h
     *
     * @param string $volumeUsd24h
     *
     * @return Coin
     */
    public function setVolumeUsd24h($volumeUsd24h)
    {
        $this->volume_usd_24h = $volumeUsd24h;

        return $this;
    }

    /**
     * Get volumeUsd24h
     *
     * @return string
     */
    public function getVolumeUsd24h()
    {
        return $this->volume_usd_24h;
    }

    /**
     * Set volumeEur24h
     *
     * @param string $volumeEur24h
     *
     * @return Coin
     */
    public function setVolumeEur24h($volumeEur24h)
    {
        $this->volume_eur_24h = $volumeEur24h;

        return $this;
    }

    /**
     * Get volumeEur24h
     *
     * @return string
     */
    public function getVolumeEur24h()
    {
        return $this->volume_eur_24h;
    }

    /**
     * Set percentChange1h
     *
     * @param string $percentChange1h
     *
     * @return Coin
     */
    public function setPercentChange1h($percentChange1h)
    {
        $this->percent_change_1h = $percentChange1h;

        return $this;
    }

    /**
     * Get percentChange1h
     *
     * @return string
     */
    public function getPercentChange1h()
    {
        return $this->percent_change_1h;
    }

    /**
     * Set percentChange24h
     *
     * @param string $percentChange24h
     *
     * @return Coin
     */
    public function setPercentChange24h($percentChange24h)
    {
        $this->percent_change_24h = $percentChange24h;

        return $this;
    }

    /**
     * Get percentChange24h
     *
     * @return string
     */
    public function getPercentChange24h()
    {
        return $this->percent_change_24h;
    }

    /**
     * Set percentChange7d
     *
     * @param string $percentChange7d
     *
     * @return Coin
     */
    public function setPercentChange7d($percentChange7d)
    {
        $this->percent_change_7d = $percentChange7d;

        return $this;
    }

    /**
     * Get percentChange7d
     *
     * @return string
     */
    public function getPercentChange7d()
    {
        return $this->percent_change_7d;
    }
}
