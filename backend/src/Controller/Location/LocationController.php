<?php

namespace App\Controller\Location;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    public $MIDDLEWARE = "jwt";

    #[Route("/api/locations", name: "list-locations", methods: ["GET"])]
    public function listLocations(Request $request, LocationRepository $locationRep): JsonResponse
    {
        $locations = $locationRep->findAll();
        $locationsSerialized = [];
        foreach ($locations as $location) {
            $locationsSerialized[] = $this->serialize($location, $request->query->get("dataType"));
        }
        return $this->json(["data" => $locationsSerialized]);
    }

    private function serialize(Location $location, string $dataType): ?array
    {
        if ($dataType === "list") {
            return $location->serializeList();
        } elseif ($dataType === "form") {
            return $location->serializeForm();
        } elseif ($dataType === "short") {
            return $location->serializeShort();
        }
        return null;
    }
}
