<?php

namespace App\Controller;

use App\Entity\Track;
use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
}
