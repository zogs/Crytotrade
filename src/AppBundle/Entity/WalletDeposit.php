<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WalletDeposit.
 *
 * @ORM\Table(name="wallet_deposit")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\WalletDepositRepository")
 */
class WalletDeposit
{
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @ORM\Column(name="fullname", type="string")
     */
    private $fullname;

    /**
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @ORM\Column(name="wallet", type="string")
     */
    private $wallet;


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
     * Set name
     *
     * @param string $name
     *
     * @return WalletDeposit
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
     * @return WalletDeposit
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
     * @param float $amount
     *
     * @return WalletDeposit
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set wallet
     *
     * @param string $wallet
     *
     * @return WalletDeposit
     */
    public function setWallet($wallet)
    {
        $this->wallet = $wallet;

        return $this;
    }

    /**
     * Get wallet
     *
     * @return string
     */
    public function getWallet()
    {
        return $this->wallet;
    }
}
