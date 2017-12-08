<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LimitCheck.
 *
 * @ORM\Table(name="limit_check")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\LimitCheckRepository")
 */
class LimitCheck
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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @ORM\Column(name="param", type="string")
     */
    private $param;

    /**
     * @ORM\Column(name="value", type="float")
     */
    private $value;

    /**
     * @ORM\Column(name="equal", type="string")
     */
    private $equal;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

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
     * @return LimitCheck
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
     * Set name
     *
     * @param string $name
     *
     * @return LimitCheck
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
     * Set param
     *
     * @param string $param
     *
     * @return LimitCheck
     */
    public function setParam($param)
    {
        $this->param = $param;

        return $this;
    }

    /**
     * Get param
     *
     * @return string
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Set value
     *
     * @param float $value
     *
     * @return LimitCheck
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set equal
     *
     * @param string $equal
     *
     * @return LimitCheck
     */
    public function setEqual($equal)
    {
        $this->equal = $equal;

        return $this;
    }

    /**
     * Get equal
     *
     * @return string
     */
    public function getEqual()
    {
        return $this->equal;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return LimitCheck
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}
