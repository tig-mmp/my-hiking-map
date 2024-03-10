<?php

namespace App\Controller;

use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TracksMoitaController extends AbstractController
{
    #[Route("/api/tracks/moita", name: "list-tracks-moita", methods: ["GET"])]
    public function listTracksMoita(TrackRepository $trackRep): JsonResponse
    {
        $tracks = $trackRep->findBy(["isMoita" => true]);
        $tracksSerialized = [];
        foreach ($tracks as $track) {
            $tracksSerialized[] = $track->serializeMap();
        }
        return $this->json(["data" => $tracksSerialized]);
    }
}
