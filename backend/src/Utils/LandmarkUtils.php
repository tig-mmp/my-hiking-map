<?php

namespace App\Utils;

use App\Dto\LandmarkFormDto;
use App\Entity\Landmark;
use App\Entity\LandmarkType;
use App\Entity\Track;
use App\Repository\LandmarkRepository;
use App\Repository\LandmarkTypeRepository;
use App\Utils\PointUtils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LandmarkUtils
{
    public function manageLandmarks(
        EntityManager $entityManager, array $landmarks, ?Track $track, PointUtils $pointUtils,
        FileUtils $fileUtils, LandmarkRepository $landmarkRep, LandmarkTypeRepository $landmarkTypeRep
    ) {
        $oldLandmarkIds = $track ? $track->getLandmarkIds() : [];
        $ids = [];
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
            $fileUtils->manageFile($entityManager, $landmarkFormDto->getFile(), $track, $landmark);
            if ($track) {
                $landmark->setIsMoita($track->getIsMoita());
            }
            $landmark->setTrack($track);
            $this->setLandmarkType($entityManager, $landmark, $landmarkFormDto->getLandmarkTypeId(), $landmarkFormDto->getLandmarkTypeName(), $landmarkTypeRep);
            $ids[] = $landmark->getId();
        }
        $idsToRemove = array_diff($oldLandmarkIds, $ids);
        if ($track && $idsToRemove) {
            foreach ($track->getLandmarks() as $landmark) {
                if (in_array($landmark->getId(), $idsToRemove)) {
                    if ($landmark->getFileId()) {
                        @unlink($landmark->getFile()->getUrl());
                        $entityManager->remove($landmark->getFile());
                    }
                    $entityManager->remove($landmark);
                }
            }
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

    private function setLandmarkType(EntityManager $entityManager, Landmark $landmark, ?int $id, ?string $name, LandmarkTypeRepository $landmarkTypeRep): ?LandmarkType
    {
        if (!$id && !$name) {
            return null;
        }
        if (!$id && $name) {
            $landmarkType = $landmarkTypeRep->findOneBy(["name" => $name]);
            if (!$landmarkType) {
                $landmarkType = new LandmarkType($name);
                $entityManager->persist($landmarkType);
            }
        } else {
            $landmarkType = $landmarkTypeRep->findOneBy(["id" => $id]);
        }
        $landmark->setLandmarkType($landmarkType);
        return $landmarkType;
    }

    public function serialize(Landmark $landmark, string $dataType): ?array
    {
        if ($dataType === "list") {
            return $landmark->serializeList();
        } elseif ($dataType === "form") {
            return $landmark->serializeForm();
        } elseif ($dataType === "map") {
            return $landmark->serializeMap();
        }
        return null;
    }
}
