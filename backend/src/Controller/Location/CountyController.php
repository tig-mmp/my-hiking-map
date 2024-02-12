<?php

namespace App\Controller\Location;

use App\Entity\County;
use App\Repository\CountyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CountyController extends AbstractController
{
    public $MIDDLEWARE = "jwt";

    #[Route("/api/counties", name: "list-counties", methods: ["GET"])]
    public function listCounties(Request $request, CountyRepository $countyRep): JsonResponse
    {
        $counties = $countyRep->findAll();
        $countiesSerialized = [];
        foreach ($counties as $county) {
            $countiesSerialized[] = $this->serialize($county, $request->query->get("dataType"));
        }
        return $this->json(["data" => $countiesSerialized]);
    }

    private function serialize(County $county, string $dataType): ?array
    {
        if ($dataType === "list") {
            return $county->serializeList();
        } elseif ($dataType === "form") {
            return $county->serializeForm();
        } elseif ($dataType === "short") {
            return $county->serializeShort();
        }
        return null;
    }
}
