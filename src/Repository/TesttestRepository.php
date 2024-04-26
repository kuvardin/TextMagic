<?php

namespace App\Repository;

use App\Entity\Testtest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Testtest>
 *
 * @method Testtest|null find($id, $lockMode = null, $lockVersion = null)
 * @method Testtest|null findOneBy(array $criteria, array $orderBy = null)
 * @method Testtest[]    findAll()
 * @method Testtest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TesttestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testtest::class);
    }

//    /**
//     * @return Testtest[] Returns an array of Testtest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Testtest
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
