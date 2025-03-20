<?php

declare(strict_types=1);

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
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function findOneByName(string $name): Currencies
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function save(Currencies $currency): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($currency);
        $entityManager->flush();
    }

    public function removeAll(): void
    {
        $this->getEntityManager()->getConnection()->executeQuery('TRUNCATE TABLE currencies');
    }

    public function hasData(): bool
    {
        $count= $this->createQueryBuilder('c')
            ->select('Count(c.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

}