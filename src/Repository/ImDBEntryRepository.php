<?php

namespace App\Repository;

use App\Entity\ImDBEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImDBEntry>
 *
 * @method ImDBEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImDBEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImDBEntry[]    findAll()
 * @method ImDBEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImDBEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImDBEntry::class);
    }

//    /**
//     * @return ImDBEntry[] Returns an array of ImDBEntry objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImDBEntry
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
