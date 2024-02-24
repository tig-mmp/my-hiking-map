<?php

namespace App\Utils;

use App\Dto\LandmarkFormDto;
use App\Entity\Landmark;
use App\Entity\Track;
use App\Repository\LandmarkRepository;
use App\Utils\PointUtils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LandmarkUtils
{
    public function manageLandmarks(EntityManager $entityManager, array $landmarks, ?Track $track, PointUtils $pointUtils, LandmarkRepository $landmarkRep)
    {
        foreach ($landmarks as $landmarkParameters) {
            $landmarkFormDto = new LandmarkFormDto($landmarkParameters);
            if (!$landmarkFormDto->getPoint()) {
                continue;
            }
            if ($landmarkFormDto->getId()) {
                $landmark = $this->update($entityManager, $landmarkFormDto, $pointUtils, $landmarkRep);
            } else {
                $landmark = $this->create($entityManager, $landmarkFormDto, $pointUtils);
            }
            $landmark->setTrack($track);
        }
    }

    private function update(EntityManager $entityManager, LandmarkFormDto $parameters, PointUtils $pointUtils, LandmarkRepository $landmarkRep): Landmark
    {
        $landmark = $landmarkRep->findOneBy(["id" => $parameters->getId()]);
        if (!$landmark) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "landmark_not_found");
        }
        if (!$parameters->getId() || !$landmark->getPointId() || $parameters->getId() !== !$landmark->getPointId()) {
            $point = $pointUtils->create($entityManager, $parameters->getPoint());
            $landmark->setPoint($point);
        }
        $landmark->setName($parameters->getName());
        return $landmark;
    }

    private function create(EntityManager $entityManager, LandmarkFormDto $parameters, PointUtils $pointUtils): Landmark
    {
        $landmark = new Landmark();
        $point = $pointUtils->create($entityManager, $parameters->getPoint());
        $landmark->setPoint($point);
        $landmark->setName($parameters->getName());
        $entityManager->persist($landmark);
        return $landmark;
    }
}
