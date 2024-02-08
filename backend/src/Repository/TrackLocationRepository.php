<?php

namespace App\Repository;

use App\Entity\TrackLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrackLocation>
 *
 * @method TrackLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrackLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrackLocation[]    findAll()
 * @method TrackLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrackLocation::class);
    }
}
