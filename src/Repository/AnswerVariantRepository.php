<?php

namespace App\Repository;

use App\Entity\AnswerVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnswerVariant>
 *
 * @method AnswerVariant|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnswerVariant|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnswerVariant[]    findAll()
 * @method AnswerVariant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerVariantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnswerVariant::class);
    }

    //    /**
    //     * @return AnswerVariant[] Returns an array of AnswerVariant objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AnswerVariant
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
