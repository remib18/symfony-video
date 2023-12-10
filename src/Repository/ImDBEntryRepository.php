<?php

namespace App\Repository;

use App\Entity\ImDBEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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




}
