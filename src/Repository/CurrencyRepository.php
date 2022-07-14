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

    public function save(Currency $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->flush();
        }
    }

    public function remove(Currency $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->flush();
        }
    }

    public function getCurrencyById(int $id)
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getLastCurrency(): ?Currency
    {
        return $this
            ->createQueryBuilder('c')
            ->orderBy('c.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function flush(): void
    {
        $this
            ->getEntityManager()
            ->flush();
    }

    /**
     * @return Currency[] array of currencyList
     */
    public function getCurrencyListByDate(int $timestamp): ?array
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.date IN (SELECT MAX(c2.date) FROM App\Entity\Currency c2 WHERE c2.date <= :timestamp)')
            ->orderBy('c.charCode', 'ASC')
            ->setParameter('timestamp', $timestamp)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Currency[] array of currencyList
     */
    public function getCurrencyListByPeriod(int $timeFrom, int $timeTo, string $valuteId): ?array
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.valuteID = :valuteid')
            ->andWhere('c.date >= :timefrom')
            ->andWhere('c.date <= :timeto')
            ->orderBy('c.date', 'DESC')
            ->setParameter(':valuteid', $valuteId)
            ->setParameter(':timefrom', $timeFrom)
            ->setParameter(':timeto', $timeTo)
            ->getQuery()
            ->getResult();
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
