<?php

namespace App\Repository;

use App\Entity\Currencies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currencies::class);
    }

    public function findAllCurrenciesName(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.name')
            ->orderBy('c.name','ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function  findOneByName(string $name)
    {
        return $this->findOneBy(['name' => $name]);
    }
}