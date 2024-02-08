<?php

namespace App\Repository;

use App\Entity\LandmarkType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LandmarkType>
 *
 * @method LandmarkType|null find($id, $lockMode = null, $lockVersion = null)
 * @method LandmarkType|null findOneBy(array $criteria, array $orderBy = null)
 * @method LandmarkType[]    findAll()
 * @method LandmarkType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LandmarkTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LandmarkType::class);
    }
}
