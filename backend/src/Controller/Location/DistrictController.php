<?php

namespace App\Controller\Location;

use App\Entity\District;
use App\Repository\DistrictRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DistrictController extends AbstractController
{
    public $MIDDLEWARE = "jwt";

    #[Route("/api/districts", name: "list-districts", methods: ["GET"])]
    public function listDistricts(Request $request, DistrictRepository $districtRep): JsonResponse
    {
        $districts = $districtRep->findAll();
        $districtsSerialized = [];
        foreach ($districts as $district) {
            $districtsSerialized[] = $this->serialize($district, $request->query->get("dataType"));
        }
        return $this->json(["data" => $districtsSerialized]);
    }

    private function serialize(District $district, string $dataType): ?array
    {
        if ($dataType === "list") {
            return $district->serializeList();
        } elseif ($dataType === "form") {
            return $district->serializeForm();
        } elseif ($dataType === "short") {
            return $district->serializeShort();
        }
        return null;
    }
}
