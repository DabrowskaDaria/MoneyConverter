<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'currencies')]
class Currencies
{
    private $id;
    private $name;

    private $buyRate;

    private $sellRate;

    private $spread;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getBuyRate()
    {
        return $this->buyRate;
    }

    /**
     * @param mixed $buyRate
     */
    public function setBuyRate($buyRate): void
    {
        $this->buyRate = $buyRate;
    }

    /**
     * @return mixed
     */
    public function getSellRate()
    {
        return $this->sellRate;
    }

    /**
     * @param mixed $sellRate
     */
    public function setSellRate($sellRate): void
    {
        $this->sellRate = $sellRate;
    }

    /**
     * @return mixed
     */
    public function getSpread()
    {
        return $this->spread;
    }

    /**
     * @param mixed $spread
     */
    public function setSpread($spread): void
    {
        $this->spread = $spread;
    }


}