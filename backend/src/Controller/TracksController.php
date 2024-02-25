<?php

namespace App\Controller;

use App\Dto\TrackFormDto;
use App\Entity\Track;
use App\Repository\CountyRepository;
use App\Repository\DistrictRepository;
use App\Repository\LandmarkRepository;
use App\Repository\LandmarkTypeRepository;
use App\Repository\LocationRepository;
use App\Repository\PointRepository;
use App\Repository\TrackRepository;
use App\Utils\FileUtils;
use App\Utils\LandmarkUtils;
use App\Utils\LocationUtils;
use App\Utils\PointUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class TracksController extends AbstractController
{
    public $MIDDLEWARE = "jwt";

    #[Route("/api/tracks", name: "list-tracks", methods: ["GET"])]
    public function listTracks(Request $request, TrackRepository $trackRep): JsonResponse
    {
        $tracks = $trackRep->findAll();
        $tracksSerialized = [];
        foreach ($tracks as $track) {
            $tracksSerialized[] = $this->serialize($track, $request->query->get("dataType"));
        }
        return $this->json(["data" => $tracksSerialized]);
    }

    #[Route("/api/tracks/{id<\d+>}", name: "find-track", methods: ["GET"])]
    public function findTrack(int $id, Request $request, TrackRepository $trackRep): JsonResponse
    {
        $track = $trackRep->findOneBy(["id" => $id]);
        if (!$track) {
            return $this->json([Response::HTTP_NOT_FOUND, "track_not_found"]);
        }
        $trackSerialized = $this->serialize($track, $request->query->get("dataType"));
        return $this->json(["data" => $trackSerialized]);
    }

    private function serialize(Track $track, string $dataType): ?array
    {
        if ($dataType === "list") {
            return $track->serializeList();
        } elseif ($dataType === "form") {
            return $track->serializeForm();
        }
        return null;
    }

    #[Route("/api/tracks", name: "create-track", methods: ["POST"])]
    public function createTrack(
        EntityManagerInterface $entityManager, Request $request, LandmarkUtils $landmarkUtils, LocationUtils $locationUtils,
        PointUtils $pointUtils, FileUtils $fileUtils, LandmarkRepository $landmarkRep, PointRepository $pointRep,
        DistrictRepository $districtRep, CountyRepository $countyRep, LocationRepository $locationRep, LandmarkTypeRepository $landmarkTypeRep
    ): JsonResponse {
        $parameters = json_decode($request->getContent(), true);
        $parameters = new TrackFormDto($parameters);

        $track = new Track();
        $locationUtils->fillStartLocation($entityManager, $track, $parameters->getStartLocationDto(), $districtRep, $countyRep, $locationRep);
        $landmarkUtils->manageLandmarks($entityManager, $parameters->getLandmarks(), $track, $pointUtils, $fileUtils, $landmarkRep, $landmarkTypeRep);
        $pointUtils->managePoints($entityManager, $parameters->getPoints(), $track, $pointRep);
        $this->fill($track, $parameters);
        $entityManager->persist($track);
        $entityManager->flush();
        $fileUtils->manageFile($entityManager, $parameters->getFile(), $track, null);
        $entityManager->flush();

        return $this->json(["msg_code" => "track_created"], JsonResponse::HTTP_CREATED);
    }

    #[Route("/api/tracks/{id<\d+>}", name: "update-track", methods: ["PUT"])]
    public function updateTrack(
        int $id, EntityManagerInterface $entityManager, Request $request, LandmarkUtils $landmarkUtils, LocationUtils $locationUtils,
        PointUtils $pointUtils, FileUtils $fileUtils, LandmarkRepository $landmarkRep, TrackRepository $trackRep, PointRepository $pointRep,
        DistrictRepository $districtRep, CountyRepository $countyRep, LocationRepository $locationRep, LandmarkTypeRepository $landmarkTypeRep
    ): JsonResponse {
        $track = $trackRep->findOneBy(["id" => $id]);
        if (!$track) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "track_not_found");
        }

        $parameters = json_decode($request->getContent(), true);
        $parameters = new TrackFormDto($parameters);
        $locationUtils->fillStartLocation($entityManager, $track, $parameters->getStartLocationDto(), $districtRep, $countyRep, $locationRep);
        $landmarkUtils->manageLandmarks($entityManager, $parameters->getLandmarks(), $track, $pointUtils, $fileUtils, $landmarkRep, $landmarkTypeRep);
        $pointUtils->managePoints($entityManager, $parameters->getPoints(), $track, $pointRep);
        $fileUtils->manageFile($entityManager, $parameters->getFile(), $track, null);
        $this->fill($track, $parameters);

        $entityManager->flush();
        return $this->json(["msg_code" => "track_updated"]);
    }

    private function fill(Track $track, TrackFormDto $parameters)
    {
        $track->setName($parameters->getName());
        $track->setTrackUrl($parameters->getUrl());
        $track->setDescription($parameters->getDescription());
        $track->setDistance($parameters->getDistance());
        $track->setSlope($parameters->getSlope());
        $track->setRouteCode($parameters->getRouteCode());
        $track->setDifficulty($parameters->getDifficulty());
        $track->setLandscape($parameters->getLandscape());
        $track->setEnjoyment($parameters->getEnjoyment());
        $track->setTrackUrl($parameters->getTrackUrl());
        $track->setOfficialUrl($parameters->getOfficialUrl());
        $track->setGroupName($parameters->getGroupName());
        $track->setGuide($parameters->getGuide());
        $track->setWeekNumber($parameters->getWeekNumber());
        $track->setIsMoita($parameters->getIsMoita());
        $track->setDuration($parameters->getDuration());
        $track->setDate($parameters->getDate());
        $track->setStartTime($parameters->getStartTime());
        $track->setEndTime($parameters->getEndTime());
    }
}
