<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Currency>
 *
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    public function save(Currency $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Currency $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getLastCurrency(): ?Currency
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function endTransaction(): void
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @return Currency[] array of Currencies
     */
    public function getExchangeRatesByDate(int $timestamp): ?array
    {
        return $this->createQueryBuilder('c')
            ->where('c.date <= :timestamp')
            ->orderBy('c.date', 'DESC')
            ->setParameter('timestamp', $timestamp)
            ->setMaxResults(34)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Currency[] array of Currencies
     */
    public function getExchangeRatesByPeriod(int $timeFrom, int $timeTo, string $valuteId): ?array
    {
        return $this->createQueryBuilder('c')
            ->where('c.valuteID = :valuteid')
            ->andWhere('c.date >= :timefrom')
            ->andWhere('c.date <= :timeto')
            ->orderBy('c.date', 'DESC')
            ->setParameter(':valuteid', $valuteId)
            ->setParameter(':timefrom', $timeFrom)
            ->setParameter(':timeto', $timeTo)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Currency[] Returns an array of Currency objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Currency
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
