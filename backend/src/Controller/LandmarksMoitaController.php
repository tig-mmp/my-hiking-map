<?php

namespace App\Controller;

use App\Repository\LandmarkRepository;
use App\Utils\LandmarkUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LandmarksMoitaController extends AbstractController
{
    #[Route("/api/landmarks/moita", name: "list-landmarks-moita", methods: ["GET"])]
    public function listLandmarksMoita(Request $request, LandmarkUtils $landmarkUtils, LandmarkRepository $landmarkRep): JsonResponse
    {
        $landmarks = $landmarkRep->findBy(["isMoita" => true]);
        $landmarksSerialized = [];
        foreach ($landmarks as $landmark) {
            $landmarksSerialized[] = $landmarkUtils->serialize($landmark, $request->query->get("dataType"));
        }
        return $this->json(["data" => $landmarksSerialized]);
    }
}
