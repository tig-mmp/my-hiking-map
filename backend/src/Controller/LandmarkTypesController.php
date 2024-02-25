<?php

namespace App\Controller;

use App\Entity\LandmarkType;
use App\Repository\LandmarkTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LandmarkTypesController extends AbstractController
{
    public $MIDDLEWARE = "jwt";

    #[Route("/api/landmark-types", name: "list-landmark-types", methods: ["GET"])]
    public function listLandmarkTypes(Request $request, LandmarkTypeRepository $landmarkTypeRep): JsonResponse
    {
        $landmarkTypes = $landmarkTypeRep->findAll();
        $landmarkTypesSerialized = [];
        foreach ($landmarkTypes as $landmarkType) {
            $landmarkTypesSerialized[] = $this->serialize($landmarkType, $request->query->get("dataType"));
        }
        return $this->json(["data" => $landmarkTypesSerialized]);
    }

    private function serialize(LandmarkType $landmarkType, string $dataType): ?array
    {
        if ($dataType === "list") {
            return $landmarkType->serializeList();
        } elseif ($dataType === "form") {
            return $landmarkType->serializeForm();
        } elseif ($dataType === "short") {
            return $landmarkType->serializeShort();
        }
        return null;
    }
}
