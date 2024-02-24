<?php

namespace App\Utils;

use App\Dto\PointFormDto;
use App\Entity\Point;
use App\Entity\Track;
use App\Repository\PointRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PointUtils
{
    public function managePoints(EntityManager $entityManager, array $points, ?Track $track, PointRepository $pointRep)
    {
        foreach ($points as $pointParameters) {
            $pointFormDto = new PointFormDto($pointParameters);
            if ($pointFormDto->getId()) {
                $point = $this->update($pointFormDto, $pointRep);
            } else {
                $point = $this->create($entityManager, $pointFormDto);
            }
            $point->setTrack($track);
        }
    }

    public function update(PointFormDto $parameters, PointRepository $pointRep): Point
    {
        $point = $pointRep->findOneBy(["id" => $parameters->getId()]);
        if (!$point) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "point_not_found");
        }
        $this->fillPoint($point, $parameters);
        return $point;
    }

    public function create(EntityManager $entityManager, PointFormDto $parameters): Point
    {
        $point = new Point();
        $this->fillPoint($point, $parameters);
        $entityManager->persist($point);
        return $point;
    }

    private function fillPoint(Point $point, PointFormDto $parameters)
    {
        $point->setElevation($parameters->getElevation());
        $point->setLatitude($parameters->getLatitude());
        $point->setLongitude($parameters->getLongitude());
        $point->setDate($parameters->getDate());
    }
}
