<?php

namespace App\Controller;

use App\Repository\LandmarkRepository;
use App\Utils\LandmarkUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandmarksController extends AbstractController
{
    public $MIDDLEWARE = "jwt";

    #[Route("/api/landmarks", name: "list-landmarks", methods: ["GET"])]
    public function listLandmarks(Request $request, LandmarkUtils $landmarkUtils, LandmarkRepository $landmarkRep): JsonResponse
    {
        $landmarks = $landmarkRep->findAll();
        $landmarksSerialized = [];
        foreach ($landmarks as $landmark) {
            $landmarksSerialized[] = $landmarkUtils->serialize($landmark, $request->query->get("dataType"));
        }
        return $this->json(["data" => $landmarksSerialized]);
    }

    #[Route("/api/landmarks/{id<\d+>}", name: "find-landmark", methods: ["GET"])]
    public function findLandmark(int $id, Request $request, LandmarkUtils $landmarkUtils, LandmarkRepository $landmarkRep): JsonResponse
    {
        $landmark = $landmarkRep->findOneBy(["id" => $id]);
        if (!$landmark) {
            return $this->json([Response::HTTP_NOT_FOUND, "landmark_not_found"]);
        }
        $landmarkSerialized = $landmarkUtils->serialize($landmark, $request->query->get("dataType"));
        return $this->json(["data" => $landmarkSerialized]);
    }
}
