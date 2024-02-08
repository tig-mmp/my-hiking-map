<?php

namespace App\Repository;

use App\Entity\Landmark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Landmark>
 *
 * @method Landmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Landmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method Landmark[]    findAll()
 * @method Landmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LandmarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Landmark::class);
    }
}
