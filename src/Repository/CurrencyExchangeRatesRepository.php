<?php

namespace App\Repository;

use App\Entity\CurrencyExchangeRates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CurrencyExchangeRates>
 *
 * @method CurrencyExchangeRates|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyExchangeRates|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyExchangeRates[]    findAll()
 * @method CurrencyExchangeRates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyExchangeRatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyExchangeRates::class);
    }

    public function save(CurrencyExchangeRates $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CurrencyExchangeRates $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CurrencyExchangeRates[] Returns an array of CurrencyExchangeRates objects
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

//    public function findOneBySomeField($value): ?CurrencyExchangeRates
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
