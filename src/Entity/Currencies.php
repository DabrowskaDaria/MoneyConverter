<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'currencies')]
class Currencies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 3)]
    private string $name;

    #[ORM\Column(type: 'float')]
    private float $buyRate;

    #[ORM\Column(type: 'float')]
    private float $sellRate;

    #[ORM\Column(type: 'float')]
    private float $spread;

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getBuyRate(): float
    {
        return $this->buyRate;
    }

    /**
     * @param mixed $buyRate
     */
    public function setBuyRate(float $buyRate): void
    {
        $this->buyRate = $buyRate;
    }

    /**
     * @return mixed
     */
    public function getSellRate(): float
    {
        return $this->sellRate;
    }

    /**
     * @param mixed $sellRate
     */
    public function setSellRate(float $sellRate): void
    {
        $this->sellRate = $sellRate;
    }

    /**
     * @return mixed
     */
    public function getSpread(): float
    {
        return $this->spread;
    }

    /**
     * @param mixed $spread
     */
    public function setSpread(float $spread): void
    {
        $this->spread = $spread;
    }


}