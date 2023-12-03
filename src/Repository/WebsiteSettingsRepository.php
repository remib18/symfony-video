<?php

namespace App\Repository;

use App\Entity\WebsiteSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebsiteSettings>
 *
 * @method WebsiteSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebsiteSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebsiteSettings[]    findAll()
 * @method WebsiteSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsiteSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebsiteSettings::class);
    }

    public function findDefault(): ?WebsiteSettings
    {
        return $this->findOneBy(['id' => 1]);
    }

//    /**
//     * @return WebsiteSettings[] Returns an array of WebsiteSettings objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WebsiteSettings
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
