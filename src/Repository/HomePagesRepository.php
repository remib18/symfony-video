<?php

namespace App\Repository;

use App\Entity\HomePages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HomePages>
 *
 * @method HomePages|null find($id, $lockMode = null, $lockVersion = null)
 * @method HomePages|null findOneBy(array $criteria, array $orderBy = null)
 * @method HomePages[]    findAll()
 * @method HomePages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HomePagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomePages::class);
    }

//    /**
//     * @return HomePages[] Returns an array of HomePages objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HomePages
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
